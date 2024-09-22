<?php 
    require_once 'header.php'; 

    $crud = new CRUD();

    $saldos_anteriores = array();
    if (isset($_POST["mesFiltro"]) && !empty($_POST["mesFiltro"])) {
        $movimentos = $crud->indexTable($_POST["mesFiltro"]);
    } else {
        $movimentos = $crud->indexTable();
        $saldos_anteriores = $crud->getSaldoPassado();
    }

    $resultado = 0;
    $acumulado = 0;
?>
     
    <main class="container">
        <form action="index.php" method="post">
            <?php require_once "select_month.php"; ?>
        </form>

        <section class="d-flex justify-content-center align-items-center flex-column">
            <form>
                <div class="input-group">
                    <div class="mb-1 p-1">
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="ml-2 p-1">
                        <button type="submit" class="btn btn-primary">Submit</button>
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