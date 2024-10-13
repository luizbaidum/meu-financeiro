<?php 
    require_once 'header.php'; 

    $crud = new CRUD();

    $pesquisa = '';
    $saldos_anteriores = array();
    $resultado = 0;
    $acumulado = 0;

    if (!empty($_POST['pesquisa']) && trim($_POST['pesquisa'], ' ') != '') {
        $pesquisa = trim($_POST['pesquisa'], ' ');
    }

    if (isset($_POST['mesFiltro']) && !empty($_POST['mesFiltro'])) {
        $movimentos = $crud->indexTable($pesquisa, $_POST['mesFiltro']);
    } else {
        $movimentos = $crud->indexTable($pesquisa);
        $saldos_anteriores = $crud->getSaldoPassado();
    }

    if ($pesquisa != '') {
        $saldos_anteriores = array();
    }

    $options_list = json_encode($crud->selectAll('categoria', [], [], []));
    echo '<script>var options_obj = ' . $options_list . '</script>';
?>
     
    <main class="container">
        <form action="index.php" data-method="post" id="idFormMesFiltro">
            <?php require_once "select_month.php"; ?>
        </form>

        <section class="d-flex justify-content-center align-items-center flex-column">
            <form class="form-ajax" data-url-action="index.php" data-method="POST">
                <div class="input-group">
                    <div class="mb-1 p-1">
                        <input type="text" class="form-control" id="idPesquisa" name="pesquisa" value="<?= $pesquisa; ?>">
                    </div>
                    <div class="ml-2 p-1">
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </div>
                </div>
            </form>
        </section>

        <table class="table">
            <theader>
                <tr>
                    <th>Data</th>
                    <th>Movimento</th>
                    <th>Categoria</th>
                    <th>Valor</th>
                    <th style="width: 90px">Editar</th>
                    <th style="width: 30px">Excluir</th>
                </tr>
            </theader>
            <tbody>
                <?php 
                    foreach ($movimentos as $mov): 
                        $resultado += $mov["valor"];
                ?>
                    <tr>
                        <td class="input-edit-movimento dataMovimento" data-element-type="input"><?= $mov['dataMovimento']; ?></td>
                        <td class="input-edit-movimento nomeMovimento" data-element-type="input"><?= $mov['nomeMovimento']; ?></td>
                        <td class="input-edit-movimento idCategoria <?= $mov['idCategoria']; ?>" data-element-type="select"><?= $mov['categoria']; ?></td>
                        <td class="input-edit-movimento valor" data-element-type="input">$ <?= $mov['valor']; ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-success salvar-edicao" id="<?= $mov['idMovimento']; ?>" title="Salvar" data-table="movimento">
                                &check;
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger cancelar-edicao" id="<?= $mov['idMovimento']; ?>" data-table="movimento" title="Cancelar">
                                &#10005;
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-warning" id="<?= $mov['idMovimento']; ?>" title="Excluir">
                                &#9904;
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <td colspan="3" style="text-align: right">Resultado (Rec. - Des. - Apli.)</td>
                    <td>$ <?= $resultado; ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php 
                    if (!empty($saldos_anteriores)): 
                        $acumulado = $resultado;
                ?>
                    <?php 
                        foreach ($saldos_anteriores as $value): 
                            $acumulado += + $value['valor'];
                    ?>
                        <tr class="table-dark">
                            <td colspan="3" style="text-align: right">Resultado mês <?= $value['MES']; ?>:</td>
                            <td>$ <?= $value['valor']; ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-dark">
                        <td colspan="3" style="text-align: right">Acumulado</td>
                        <td>$ <?= $acumulado; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>
    </main>

<?php include_once 'bottom.php'; ?>