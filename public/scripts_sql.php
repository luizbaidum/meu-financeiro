<?php

require_once "diretorio.php";
require_once Diretorio::diretorio . "\\table_names\\table_names.php";
require_once Diretorio::diretorio ."\\connection\\conexao.php";

class CRUD {
    private function executarQuery($query, $arr_values = [])
    {
        $operacao = strtoupper(strtok($query, " "));

        $stmt = gerarConexao()->prepare($query);

        if (!empty($arr_values))
            $stmt->execute($arr_values);
        else
            $stmt->execute();

        //$stmt->debugDumpParams();
        //exit;

        switch ($operacao) {
            case "INSERT":
            case "UPDATE":
            case "DELETE":
                $result = $stmt->rowCount();
                break;
            case "SELECT":
            case "SHOW":
                $retornar_select = $stmt->fetchAll();
                $result = $retornar_select;
                break;
        }

        return $result;
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

    public function delete(string $action, array $get)
    {
        $arr_values = array();
        $table = TableNames::getTableName($action);

        foreach ($get as $k => $v) {
            $campo = $k;
            $valor = $v;
        }

        $query = "DELETE FROM `$table` WHERE `$campo` = ?"; 

        $arr_values[] = $valor;

       return $this->executarQuery($query, $arr_values);
    }

    public function update(string $action, array $post)
    {
        $arr_values = array();
        $table = TableNames::getTableName($action);

        $query = "UPDATE $table SET (";

        foreach ($post as $k => $v) {
            $query .= "$k = ?, ";
            $arr_values[] = $v;
        }

        $query = rtrim($query, ", ") . ")";

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
                $order .= "$column $cond";
        }

        $query = "SELECT $table.* FROM $table $where $group $order";

        return $this->executarQuery($query);
    }

    public function indexTable($month = "")
    {
        $where = " AND (MONTH(movimentos.dataMovimento) = MONTH(CURRENT_DATE()))";
        if (!empty($month))
            $where = " AND (MONTH(movimentos.dataMovimento) = '$month')";

        $query = "SELECT movimentos.*, categoria_movimentos.categoria, categoria_movimentos.tipo
                    FROM movimentos 
                    INNER JOIN categoria_movimentos ON categoria_movimentos.idCategoria = movimentos.idCategoria
                    WHERE 0 = 0 $where
                    ORDER BY dataMovimento DESC";

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
}