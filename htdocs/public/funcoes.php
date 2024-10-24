<?php

include '../sql/scripts_sql.php';

$function = $_GET['function'];
call_user_func($function, $_POST);

function buscarOrcamento($data)
{
    $ano_busca = $data['anoOrigem'] ?? '';
    $mes_busca = $data['mesOrigem'] ?? '';

    if ($ano_busca == '' && $mes_busca == '') {
        return;
    }

    $crud = new CRUD();
    $resultado = $crud->buscarMediasDespesas($ano_busca, $mes_busca);

    echo montarHtmlOrcamento($resultado);
}

function montarHtmlOrcamento($dados)
{
    if (empty($dados)) {
        return '<div class="card mt-2 text-center">Nenhum dado encontrado.</div>';
    }

    $conteudo = '';

    //montar cabeçalho e tabela
    
    foreach ($dados as $v) {
        $conteudo .= '<td>a</td>';
    }

    $str = '<div class="card mt-2 text-center">
                <table>' . $conteudo . '</table>
        </div>';

    return $str;
}
