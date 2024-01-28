<?php

require_once "diretorio.php";
require_once Diretorio::diretorio . "\\table_names\\table_names.php";
require_once Diretorio::diretorio . "\\connection\\conexao.php";

class CRUD {
    private function executarQuery($query, $arr_values = [])
    {
        $operacao = strtoupper(strtok($query, " "));

        $stmt = $con->prepare($query);

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
                $retornar_select = $stmt->fetchAll(\PDO::FETCH_OBJ);
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

    public function select(string $action, array $get)
    {
        $arr_values = array();
        $table = TableNames::getTableName($action);

        foreach ($get as $k => $v) {
            $campo = $k;
            $valor = $v;
        }

        $query = "SELECT * FROM $table WHERE $campo = ?";
        $arr_values[] = $valor;

        return $this->executarQuery($query, $arr_values);
    }

    public function selectAll()
    {
        
    }
}