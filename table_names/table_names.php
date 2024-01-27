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
        "add_categoria" => "categoria_receitas_despesas",
        "del_categoria" => "categoria_receitas_despesas",
        "add_despesa"   => "receitas_despesas",
        "del_despesa"   => "receitas_despesas",
        "add_receita"   => "receitas_despesas",
        "del_receita"   => "receitas_despesas"
    ];
}