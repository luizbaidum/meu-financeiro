<?php

require_once '../public/diretorio.php';
require_once Diretorio::diretorio . "\\table_names\\table_names.php";
require_once Diretorio::diretorio ."\\connection\\conexao.php";

class CRUD {
    private function executarQuery($query, $arr_values = [])
    {
        $operacao = strtoupper(strtok($query, " "));
        $bd = gerarConexao();
        $stmt = $bd->prepare($query);

        try {
            $bd->beginTransaction();

            if (!empty($arr_values))
                $stmt->execute($arr_values);
            else
                $stmt->execute();

            //$stmt->debugDumpParams();
            //exit;
            switch ($operacao) {
                case "INSERT":
                    $result = $bd->lastInsertId();
                    break;
                case "UPDATE":
                case "DELETE":
                    $result = $stmt->rowCount();
                    break;
                case "SELECT":
                case "SHOW":
                    $retornar_select = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $result = $retornar_select;
                    break;
            }

            $bd->commit();

            return $result;
        } catch (PDOException $e) {
            $bd->rollBack();
            echo "Failed: " . $e->getMessage();
        }

        $bd = NULL;
    }

    public function insert(string $action, array $post)
    {
        $arr_values = array();
        $table = TableNames::getTableName($action);

        $query = "INSERT INTO $table (";

        foreach ($post as $k => $v)
            $query .= "$k, ";

        $query = rtrim($query, ", ") . ")";

        $query .= "VALUES (";

        foreach ($post as $k => $v) {
            $query .= "?, ";
            $arr_values[] = $v;
        }

        $query = rtrim($query, ", ") . ")";

       return $this->executarQuery($query, $arr_values);
    }

    public function delete(array $get)
    {
        $arr_values = array();
        $table = TableNames::getTableName($get['action']);

        unset($get['action']);

        foreach ($get as $k => $v) {
            $campo = $k;
            $valor = $v;
        }

        $query = "DELETE FROM `$table` WHERE `$campo` = ?"; 

        $arr_values[] = $valor;

       return $this->executarQuery($query, $arr_values);
    }

    public function update(string $action, array $values, array $where_condition)
    {
        $arr_values = array();
        $table = TableNames::getTableName($action);

        $query = "UPDATE $table SET ";

        foreach ($values as $k => $v) {
            $query .= "$k = ?, ";
            $arr_values[] = $v;
        }

        $query = rtrim($query, ", ");

        $where_column = array_key_first($where_condition);
        $where_value = $where_condition[$where_column];

        $query .= " WHERE $where_column = $where_value";

        return $this->executarQuery($query, $arr_values);
    }

    //selectAll(action: "movimento", where_conditions: [['valor', '>', '15000']], group_conditions: ['tabela', 'coluna', 'tabela2', 'coluna2'], order_conditions: ['dataMovimento' => 'DESC']);
    public function selectAll($action, array $where_conditions, array $group_conditions, array $order_conditions)
    {
        $table = TableNames::getTableName($action);

        $where = "";
        $group = "";
        $order = "";

        if (!empty($where_conditions)) {
            $where = "WHERE ";
            foreach ($where_conditions as $part)
                $where .= "$part[0] $part[1] $part[2]";
                //column, condition, value
                //Ex.: coluna > 1
        }

        if (!empty($group_conditions)) {
            $group = "GROUP BY ";

            $total = count($group_conditions);

            for ($i = 0; $i < $total; $i += 2)
                $group .= $group_conditions[$i] . "." . $group_conditions[$i + 1] . ", ";                

            $group = rtrim($group, ", ");
        }

        if (!empty($order_conditions)) {
            $order = "ORDER BY ";
            foreach ($order_conditions as $column => $cond)
                $order .= "$column $cond,";

            $order = rtrim($order, ",");
        }

        $query = "SELECT $table.* FROM $table $where $group $order";

        return $this->executarQuery($query);
    }

    public function indexTable($month = "")
    {
        $where = " AND (MONTH(movimentos.dataMovimento) = MONTH(CURRENT_DATE()))";
        if (!empty($month))
            $where = " AND (MONTH(movimentos.dataMovimento) = '$month')";

        if ($month == "13")
            $where = "";

        $query = "SELECT movimentos.*, categoria_movimentos.categoria, categoria_movimentos.tipo
                    FROM movimentos 
                    INNER JOIN categoria_movimentos ON categoria_movimentos.idCategoria = movimentos.idCategoria
                    WHERE 0 = 0 $where
                    ORDER BY dataMovimento DESC";

        return $this->executarQuery($query);
    }

    public function getSaldoPassado(int $times = 2)
    {
        $mes_atual = date('m');
        $where = 'MONTH(movimentos.dataMovimento) BETWEEN "'. ($mes_atual - $times).'" AND "'. ($mes_atual - 1).'"';

        $query = "SELECT SUM(movimentos.valor) AS valor, MONTH(movimentos.dataMovimento) AS MES
                    FROM movimentos 
                    WHERE $where
                    GROUP BY MES";

        return $this->executarQuery($query);
    }

    public function fazerLogin($dados)
    {
        $arr_values = array();

        $query = "SELECT idUsuario FROM usuarios WHERE usuarios.login = ? AND usuarios.senha = ?";
        $arr_values[] = $dados["login"];
        $arr_values[] = $dados["senha"];

        $result = $this->executarQuery($query, $arr_values);

        if (count($result) == 1 && isset($result[0]["idUsuario"]) && !empty($result[0]["idUsuario"]))
            return true;

        return false;
    }

    public function indicadores($month = "")
    {
        $where = " AND (MONTH(movimentos.dataMovimento) = MONTH(CURRENT_DATE()))";
        if (!empty($month))
            $where = " AND (MONTH(movimentos.dataMovimento) = '$month')";

        if ($month == "13")
            $where = "";

        $query = "SELECT SUM(movimentos.valor) AS total, categoria_movimentos.idCategoria, categoria_movimentos.categoria, categoria_movimentos.tipo
                    FROM movimentos 
                    INNER JOIN categoria_movimentos ON categoria_movimentos.idCategoria = movimentos.idCategoria
                    WHERE 0 = 0 $where
                    GROUP BY movimentos.idCategoria
                    ORDER BY total DESC";

        return $this->executarQuery($query);
    }

    public function orcamentos($month = "")
    {
        $where = " AND (MONTH(orcamentos.dataOrcamento) = MONTH(CURRENT_DATE()))";
        if (!empty($month))
            $where = " AND (MONTH(orcamentos.dataOrcamento) = '$month')";

        if ($month == "13")
            $where = "";

        $query = "SELECT SUM(orcamentos.valor) AS totalOrcado, 
                            categoria_movimentos.idCategoria, 
                            categoria_movimentos.categoria, 
                            categoria_movimentos.tipo, 
                            MONTH(orcamentos.dataOrcamento) AS mesOrcado
                    FROM orcamentos 
                    INNER JOIN categoria_movimentos ON categoria_movimentos.idCategoria = orcamentos.idCategoria
                    WHERE 0 = 0 $where
                    GROUP BY orcamentos.idCategoria
                    ORDER BY totalOrcado DESC";

        return $this->executarQuery($query);
    }

    public function getSaldoAtual($action, $id_where)
    {
        $table = TableNames::getTableName($action);
        switch ($table) {
            case 'contas_investimentos':
                $column = 'idContaInvest';
                break;
            case 'objetivos_invest':
                $column = 'idObj';
                break;
        }

        $arr_values = array();

        $query = "SELECT $table.saldoAtual FROM $table WHERE $table.$column = ?";

        $arr_values[] = $id_where;

        $result = $this->executarQuery($query, $arr_values);

        return $result[0]["saldoAtual"];
    }

    public function getMensais()
    {
        $query = 'SELECT movimentos_mensais.*, 
                 categoria_movimentos.categoria, 
                 categoria_movimentos.tipo,
                 categoria_movimentos.sinal
            FROM movimentos_mensais 
            INNER JOIN categoria_movimentos ON movimentos_mensais.idCategoria = categoria_movimentos.idCategoria
            WHERE 0 = 0;';

        $result = $this->executarQuery($query, []);

        return $result;
    }

    public function validarPercentualDisponivel($id_conta_invest, $percentual_obj)
    {
        $arr_values = array();

        $query = 'SELECT SUM(objetivos_invest.percentObjContaInvest) AS totalUtilizado FROM objetivos_invest WHERE objetivos_invest.idContaInvest = ?';
        $arr_values[] = $id_conta_invest;

        $total = $this->executarQuery($query, $arr_values)[0]['totalUtilizado'];

        if ($percentual_obj > (100 - $total)) {
            return $total;
        }

        return false;
    }

    public function atualizarSaldoObj($id_obj, $percentual_obj, $id_conta_invest)
    {
        $arr_values = array();

        $query = 'SELECT contas_investimentos.saldoAtual FROM contas_investimentos WHERE contas_investimentos.idContaInvest = ?';
        $arr_values[0] = $id_conta_invest;

        $atual = $this->executarQuery($query, $arr_values)[0]['saldoAtual'];

        $vlr_obj = $atual * ($percentual_obj / 100);

        $this->update('obj', ['saldoAtual' => $vlr_obj], ['idObj' => $id_obj]); 
    }

    public function validarObjetivoComConta($id_obj, $id_conta_invest) 
    {
        $arr_values = array();

        $query = 'SELECT objetivos_invest.idObj FROM objetivos_invest WHERE objetivos_invest.idObj = ? AND objetivos_invest.idContaInvest = ?';
        $arr_values[] = $id_obj;
        $arr_values[] = $id_conta_invest;

        $result = $this->executarQuery($query, $arr_values);

        if (empty($result)) {
            return false;
        }

        return true;
    }
}