<?php 
    require_once 'header.php'; 

    $crud = new CRUD();

    $pesquisa = $_POST['pesquisa'] ?? '';
    $saldos_anteriores = array();
    if (isset($_POST['mesFiltro']) && !empty($_POST['mesFiltro'])) {
        $movimentos = $crud->indexTable($pesquisa, $_POST['mesFiltro']);
    } else {
        $movimentos = $crud->indexTable($pesquisa);
        $saldos_anteriores = $crud->getSaldoPassado();
    }

    if ($pesquisa != '') {
        $saldos_anteriores = array();
    }

    $resultado = 0;
    $acumulado = 0;
?>
     
    <main class="container">
        <form action="index.php" data-method="post" id="idFormMesFiltro">
            <?php require_once "select_month.php"; ?>
        </form>

        <section class="d-flex justify-content-center align-items-center flex-column">
            <form class="form-ajax" data-url-action="index.php" data-method="POST">
                <div class="input-group">
                    <div class="mb-1 p-1">
                        <input type="text" class="form-control" id="idPesquisa" name="pesquisa">
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
                </tr>
            </theader>
            <tbody>
                <?php 
                    foreach ($movimentos as $mov): 
                        $resultado += $mov["valor"];
                ?>
                    <tr>
                        <td><?= $mov["dataMovimento"]; ?></td>
                        <td><?= $mov["nomeMovimento"]; ?></td>
                        <td><?= $mov["categoria"]; ?></td>
                        <td>$ <?= $mov["valor"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <td colspan="3" style="text-align: right">Resultado (Rec. - Des. - Apli.)</td>
                    <td>$ <?= $resultado; ?></td>
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
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-dark">
                        <td colspan="3" style="text-align: right">Acumulado</td>
                        <td>$ <?= $acumulado; ?></td>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>
    </main>

<?php include_once 'bottom.php'; ?>