<?php

require_once 'diretorio.php';
require_once Diretorio::diretorio . "\\htdocs\\table_names\\table_names.php";
require_once Diretorio::diretorio . "\\htdocs\\connection\\conexao.php";

class retornoAjax
{
    public $status;
    public $txt;

    public function enviarRetorno(bool $status, string $txt)
    {
        echo json_encode(
            array('status' => $status, 'txt' => $txt)
        );

        exit;
    }
}

class CRUD extends retornoAjax {

    private $family_user = null;

    private function getFamilyUser()
    {
        return $this->family_user;
    }

    private function setFamilyUser($family)
    {
        $this->family_user = $family;

        return $this->family_user;
    }

    private function defineFamilyUser()
    {
        try {
            if (!isset($_SESSION)) {
                session_start();
            }
    
            if (isset($_SESSION['id_familia']) && !empty($_SESSION['id_familia'])) {
                $this->setFamilyUser($_SESSION['id_familia']);
            } else {
                $query = 'SELECT idFamilia FROM usuarios WHERE idUsuario = ?';
                $ret = $this->executarQuery($query, [$_SESSION['user']], false);

                if (empty($ret)) {
                    throw new Exception('idFamilia não encontrado.');
                }
    
                $_SESSION['id_familia'] = $ret[0]['idFamilia'];
                $this->setFamilyUser($ret[0]['idFamilia']);
            }

            return $this->getFamilyUser();

        } catch (Exception $e) {
            echo 'Security fail: ' . $e->getMessage();
            exit;
        }
    }

    private function setWhereSecurity($operacao, $query)
    {
        $id_family = $this->defineFamilyUser();
        /**
         * TODO: falta delete e update;
         */
        if ($operacao == 'SELECT' || $operacao == 'SHOW') {
            try {
                $arr_query = explode(' ', $query);
                $from_key = array_search('FROM', $arr_query);
                $table = $arr_query[$from_key + 1];

                if ($from_key == false) {
                    throw new Exception('FROM clause not found');
                }
    
                $where_key = array_search('WHERE', $arr_query);
                if ($where_key !== false) {
                    $id_into_where = " ($table.idFamilia = $id_family) AND ";
                    $arr_query[$where_key] .= $id_into_where;
    
                    $query = implode(' ', $arr_query);
                } else {
                    throw new Exception('WHERE clause not found');
                }
            } catch (Exception $e) {
                echo 'Security fail: ' . $e->getMessage();
                exit;
            }
        }

        if ($operacao == 'INSERT') {
            $arr_query = explode(')', $query);

            $arr_query[0] .= ', idFamilia)';
            $arr_query[1] .= ', ' . $id_family . ')';

            $query = $arr_query[0] . $arr_query[1];
        }

        return $query;
    }

    private function executarQuery($query, $arr_values = [], $apply_security = true)
    {
        /**
         * remove 'enters' dados na string.
         */
        $query = trim(preg_replace('/\s\s+/', ' ', $query));

        $operacao = strtok($query, ' ');

        /**
         * TODO: falta delete e update;
         */
        if ($apply_security) {
            $query = $this->setWhereSecurity($operacao, $query);
        }

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
                case 'INSERT':
                    $result = $bd->lastInsertId();
                    break;
                case 'UPDATE':
                case 'DELETE':
                    $result = $stmt->rowCount();
                    break;
                case 'SELECT':
                case 'SHOW':
                    $retornar_select = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $result = $retornar_select;
                    break;
                default:
                    throw new PDOException('Operação não reconhecida.');
            }

            $bd->commit();
            $bd = NULL;

            return $result;
        } catch (PDOException $e) {
            $bd->rollBack();
            echo 'Failed: ' . $e->getMessage();
        }
    }

    public function insert(string $action, array $post)
    {
        $arr_values = array();
        $table = TableNames::getTableName($action);

        $query = "INSERT INTO $table (";

        foreach ($post as $k => $v)
            $query .= "$k, ";

        $query = rtrim($query, ', ') . ')';

        $query .= 'VALUES (';

        foreach ($post as $k => $v) {
            $query .= '?, ';
            $arr_values[] = $v;
        }

        $query = rtrim($query, ', ') . ')';

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

        $query = rtrim($query, ', ');

        $where_column = array_key_first($where_condition);
        $where_value = $where_condition[$where_column];

        $query .= " WHERE $where_column = $where_value";

        return $this->executarQuery($query, $arr_values);
    }

    //selectAll(action: "movimento", where_conditions: [['valor', '>', '15000']], group_conditions: ['tabela', 'coluna', 'tabela2', 'coluna2'], order_conditions: ['dataMovimento' => 'DESC']);
    public function selectAll($action, array $where_conditions, array $group_conditions, array $order_conditions, bool $apply_security = true)
    {
        $table = TableNames::getTableName($action);

        $where = 'WHERE 0 = 0';
        $group = '';
        $order = '';

        if (!empty($where_conditions)) {
            $where = 'WHERE ';
            foreach ($where_conditions as $part)
                $where .= "$part[0] $part[1] $part[2]";
                //column, condition, value
                //Ex.: coluna > 1
        }

        if (!empty($group_conditions)) {
            $group = 'GROUP BY ';

            $total = count($group_conditions);

            for ($i = 0; $i < $total; $i += 2)
                $group .= $group_conditions[$i] . '.' . $group_conditions[$i + 1] . ', ';                

            $group = rtrim($group, ', ');
        }

        if (!empty($order_conditions)) {
            $order = 'ORDER BY ';
            foreach ($order_conditions as $column => $cond)
                $order .= "$column $cond,";

            $order = rtrim($order, ',');
        }

        $query = "SELECT $table.* FROM $table $where $group $order";

        return $this->executarQuery(query: $query, apply_security: $apply_security);
    }

    public function indexTable($pesquisa, $month = '')
    {
        $where = 'WHERE (MONTH(movimentos.dataMovimento) = MONTH(CURRENT_DATE()))';

        if ($month != '' && $month == 'Todos') {
            $where = 'WHERE movimentos.dataMovimento IS NOT NULL';
        } elseif ($month != '' && $month != 'Todos') {
            $where = "WHERE DATE_FORMAT(movimentos.dataMovimento, '%b') = '$month'";
        }

        if ($pesquisa != '') {
            $where .= ' AND (categoria_movimentos.categoria LIKE "%' . $pesquisa . '%" OR movimentos.nomeMovimento LIKE "%' . $pesquisa . '%")';
        }

        $query = "SELECT movimentos.*, categoria_movimentos.categoria, categoria_movimentos.tipo FROM movimentos INNER JOIN categoria_movimentos ON categoria_movimentos.idCategoria = movimentos.idCategoria $where ORDER BY dataMovimento DESC";

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

        $query = 'SELECT idUsuario, idFamilia FROM usuarios WHERE usuarios.login = ? AND usuarios.senha = ?';
        $arr_values[] = $dados['login'];
        $arr_values[] = $dados['senha'];

        $result = $this->executarQuery($query, $arr_values, false);

        if (count($result) == 1 && !empty($result[0]['idUsuario']))
            return $result[0];

        return false;
    }

    public function indicadores($month = "")
    {
        $where = 'WHERE (MONTH(movimentos.dataMovimento) = MONTH(CURRENT_DATE()))';
        if (!empty($month)) {
            if ($month == 'Todos') {
                $where = 'WHERE movimentos.dataMovimento IS NOT NULL';
            } else {
                $where = "WHERE DATE_FORMAT(movimentos.dataMovimento, '%b') = '$month'";
            }
        }

        $query = "SELECT SUM(movimentos.valor) AS total, categoria_movimentos.idCategoria, categoria_movimentos.categoria, categoria_movimentos.tipo
                    FROM movimentos 
                    INNER JOIN categoria_movimentos ON categoria_movimentos.idCategoria = movimentos.idCategoria
                    $where
                    GROUP BY movimentos.idCategoria
                    ORDER BY total DESC";

        $result = $this->executarQuery($query);

        $ret = [];
        foreach ($result as $val) {
            $ret[$val['idCategoria']] = $val;
        }

        return $ret;
    }

    public function orcamentos($month = '')
    {
        $where = 'WHERE (MONTH(orcamentos.dataOrcamento) = MONTH(CURRENT_DATE()))';
        if (!empty($month)) {
            if ($month == 'Todos') {
                $where = 'WHERE orcamentos.dataOrcamento IS NOT NULL';
            } else {
                $where = "WHERE DATE_FORMAT(orcamentos.dataOrcamento, '%b') = '$month'";
            }
        }

        $query = "SELECT SUM(orcamentos.valor) AS totalOrcado, 
                            categoria_movimentos.idCategoria, 
                            categoria_movimentos.categoria, 
                            categoria_movimentos.tipo, 
                            MONTH(orcamentos.dataOrcamento) AS mesOrcado,
                            GROUP_CONCAT(orcamentos.idOrcamento SEPARATOR ',') AS idOrcamento
                    FROM orcamentos 
                    INNER JOIN categoria_movimentos ON categoria_movimentos.idCategoria = orcamentos.idCategoria
                    $where
                    GROUP BY orcamentos.idCategoria
                    ORDER BY totalOrcado DESC";

        $result = $this->executarQuery($query);

        $ret = [];
        foreach ($result as $val) {
            $ret[$val['idCategoria']] = $val;
        }

        return $ret;
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

        return $result[0]['saldoAtual'];
    }

    public function getMensais()
    {
        $query = 'SELECT movimentos_mensais.*, categoria_movimentos.categoria, categoria_movimentos.tipo, categoria_movimentos.sinal FROM movimentos_mensais INNER JOIN categoria_movimentos ON movimentos_mensais.idCategoria = categoria_movimentos.idCategoria WHERE movimentos_mensais.idMovMensal > 0';

        $result = $this->executarQuery($query, []);

        return $result;
    }

    public function consultarPercentualDisponivel($id_conta_invest)
    {
        $arr_values = array();

        $query = 'SELECT SUM(objetivos_invest.percentObjContaInvest) AS totalUtilizado FROM objetivos_invest WHERE objetivos_invest.idContaInvest = ?';
        $arr_values[] = $id_conta_invest;

        $total = $this->executarQuery($query, $arr_values)[0]['totalUtilizado'];

        return $total;
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

    public function consultarExtrato($filtro)
    {
        $mes = $filtro['extratoMes'] ?? '';
        $invest = $filtro['extratoInvest'] ?? '';
        $acao = $filtro['acaoInvest'] ?? '';

        if ($mes == '') {
            $hoje = date('Y-m-d');
            $data_create = date_create($hoje);
            date_sub($data_create, date_interval_create_from_date_string('90 days'));
    
            $where = "AND `rendimentos`.`dataRendimento` BETWEEN '" . date_format($data_create, 'Y-m-01') . "' AND '$hoje'";
        } elseif ($mes == 'Todos') {
            $where = '';
        } else {
            $where = "AND DATE_FORMAT(`rendimentos`.`dataRendimento`, '%b') = '$mes'";
        }

        if ($invest != '') {
            $where .= "AND `rendimentos`.`idContaInvest` = '$invest'";
        }

        if ($acao != '') {
            $where .= "AND `rendimentos`.`tipo` = '$acao'";
        }

        $query = "SELECT `rendimentos`.*, CONCAT(`contas_investimentos`.`nomeBanco`, ' - ', `contas_investimentos`.`tituloInvest`) AS conta FROM `rendimentos` INNER JOIN `contas_investimentos` ON `rendimentos`.`idContaInvest` = `contas_investimentos`.`idContaInvest` WHERE `rendimentos`.`idRendimento` > 0 $where ORDER BY `rendimentos`.`dataRendimento` DESC";

        $result = $this->executarQuery($query);

        return $result;
    }

    public function buscarMediasDespesas($year, $month)
    {
        $where = "DATE_FORMAT(movimentos.dataMovimento, '%Y') = '$year'";
        $media = "(SUM(movimentos.valor) / MONTH(NOW())) AS valorOrcamento";
        if (!is_null($month) && $month != '') {
            $where = "DATE_FORMAT(movimentos.dataMovimento, '%Y-%m') = '$month'";
            $media = "SUM(movimentos.valor) AS valorOrcamento";
        }

        $query = "SELECT movimentos.idCategoria,
                    $media,
                    categoria_movimentos.categoria,
                    categoria_movimentos.sinal
                    FROM movimentos
                    INNER JOIN categoria_movimentos ON movimentos.idCategoria = categoria_movimentos.idCategoria
                    WHERE $where
                    GROUP BY movimentos.idCategoria";

        $result = $this->executarQuery($query);

        return $result;
    }
}