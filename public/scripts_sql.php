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

    //selectAll(action: "movimento", where_conditions: [['valor', '>', '15000']], order_conditions: ['dataMovimento' => 'DESC']);
    public function selectAll($action, array $where_conditions, array $order_conditions)
    {
        $table = TableNames::getTableName($action);

        $where = "";
        $order = "";

        if (!empty($where_conditions)) {
            $where = "WHERE ";
            foreach ($where_conditions as $part)
                $where .= "$part[0] $part[1] $part[2]";
                //column, condition, value
                //coluna > 1
        }

        if (!empty($order_conditions)) {
            $order = "ORDER BY ";
            foreach ($order_conditions as $column => $cond)
                $order .= "$column $cond";
        }

        $query = "SELECT $table.* FROM $table $where $order";

        return $this->executarQuery($query);
    }

    public function indexTable()
    {
        $query = "SELECT movimentos.*, categoria_movimentos.categoria, categoria_movimentos.tipo
                    FROM movimentos 
                    INNER JOIN categoria_movimentos ON categoria_movimentos.idCategoria = movimentos.idCategoria
                    WHERE 0 = 0 
                    ORDER BY dataMovimento DESC";

        return $this->executarQuery($query);
    }
}