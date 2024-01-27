<?php

require_once "diretorio.php";
require_once Diretorio::diretorio . "\\table_names\\table_names.php";

class CRUD {
    function insert(string $action, array $post)
    {
        $table = TableNames::getTableName($action);

        $query = "INSERT INTO $table (";

        foreach ($post as $k => $v)
            $query .= "$k, ";

        $query = rtrim($query, ", ") . ")";

        $query .= "VALUES (";

        foreach ($post as $k => $v)
            $query .= "$v, ";

        $query = rtrim($query, ", ") . ")";

    }
}