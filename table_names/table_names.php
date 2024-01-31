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
        "add_categoria" => "categoria_movimentos",
        "del_categoria" => "categoria_movimentos",
        "add_movimentos" => "movimentos",
        "del_movimentos" => "movimentos"
    ];
}