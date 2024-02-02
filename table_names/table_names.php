<?php 

abstract class TableNames {
    public static function getTableName(string $action)
    {
        $ret = "";

        $tables = new TablesList();

        $ret = $tables->arr_tables[$action];

        $tables = NULL;

        return $ret;
    }
}

class TablesList {

    public $table_names = array();

    public $arr_tables = [
        "categoria" => "categoria_movimentos",
        "movimento" => "movimentos"
    ];
}