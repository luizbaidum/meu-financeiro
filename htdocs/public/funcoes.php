<?php

include '../sql/scripts_sql.php';

$function = $_GET['function'];
call_user_func($function, $_POST);

function buscarOrcamento($data)
{
    $ano_busca = $data['anoOrigem'] ?? '';
    $mes_busca = $data['mesOrigem'] ?? '';
    $destino = $data['destino'] ?? '';

    if ($ano_busca == '' && $mes_busca == '') {
        return;
    }

    if ($destino == '') {
        echo '<div class="card mt-2 text-danger text-center">Por favor, selecionar data destino do orçamento.</div>';
        return;
    }

    $crud = new CRUD();
    $resultado = $crud->buscarMediasDespesas($ano_busca, $mes_busca);

    echo montarHtmlOrcamento($resultado, $destino);
}

function montarHtmlOrcamento($dados, $data_destino)
{
    if (empty($dados)) {
        return '<div class="card mt-2 text-center">Nenhum dado encontrado.</div>';
    }
    
    $cabecalho = '<thead>
                    <tr>
                        <th>selecionar</th>
                        <th>Categoria</th>
                        <th>Valor</th>
                    </tr>
                </thead>';

    $conteudo = '<tbody>';
    foreach ($dados as $v) {
        $conteudo .= '<tr>
                        <td>
                            <input type="checkbox" value="' . $v['idCategoria'] . '" name="idCategoria[]">
                        </td>
                        <td>' . $v['idCategoria'] . ' - ' . $v['categoria'] . '</td>
                        <td><input type="number" name="valor[]" value="' . $v['valorOrcamento'] . '"</td>
                    </tr>';
    }
    $conteudo .= '</tbody>';

    $footer = '<tfoot>
            </tfoot>';

    $tabela = "<table class='table'>
                    $cabecalho $conteudo $footer
                </table>";

    $form = "<form action='../sql/cad_orcamento.php' method='post'>
                $tabela
                <input type='hidden' name='dataOrcamento' value='$data_destino'>
                <input type='hidden' name='lote' value='T'>
                <button type='submit' class='btn btn-primary mt-1'>Salvar</button>
            </form>";

    $card_table = "<div class='card mt-2'>
                        <div class='card-body'>
                            $form
                        </div>
                    </div>";

    return $card_table;
}
