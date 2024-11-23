<?php 

class TableNames extends TablesList {
    public static function getTableName(string $action)
    {
        return self::$arr_tables[$action];
    }
}

abstract class TablesList {
    static $arr_tables = [
        'categoria'          => 'categoria_movimentos',
        'movimento'          => 'movimentos',
        'orcamento'          => 'orcamentos',
        'lembrete'           => 'lembretes',
        'conta_investimento' => 'contas_investimentos',
        'rendimento'         => 'rendimentos',
        'mensais'            => 'movimentos_mensais',
        'obj'                => 'objetivos_invest',
        'erros'              => 'erros_bog'
    ];
}