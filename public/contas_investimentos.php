
<?php
    require_once ("header.php");

    $crud = new CRUD();

    if (!empty($_POST)) {
        $_POST["saldoAtual"] = $_POST["saldoInicial"];
        $crud->insert("conta_investimento", $_POST);
    }

    $contas = $crud->selectAll("conta_investimento", [], [], []);

    $total = 0;
?>

    <main class="container">
        <div class="card p-1">
            <form action="contas_investimentos.php" method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <label for="idNomeBanco">Nome Banco</label>
                            <input type="text" class="form-control" id="idNomeBanco" name="nomeBanco" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="idTituloInvest">Título</label>
                            <input type="text" class="form-control" id="idTituloInvest" name="tituloInvest" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="idDataInicio">Data Início (m/d/a)</label>
                            <input type="date" class="form-control" id="idDataInicio" name="dataInicio" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12">
                                <label for="idSaldoInicial">Saldo de início (decimal com ponto)</label>
                                <input type="number" class="form-control" id="idSaldoInicial" name="saldoInicial" step=".01" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </form>
        </div>

        <div class="card mt-1">
            <div class="card-body">
                <table class="table">
                    <theader>
                        <tr>
                            <th>Banco</th>
                            <th>Título</th>
                            <th>Saldo inicial</th>
                            <th>Saldo atual</th>
                        </tr>
                    </theader>
                    <tbody>
                        <?php 
                            foreach ($contas as $value): 
                                $total += $value["saldoAtual"];
                        ?>
                            <tr>
                                <td><?= $value["nomeBanco"]; ?></td>
                                <td><?= $value["tituloInvest"]; ?></td>
                                <td>$ <?= $value["saldoInicial"]; ?></td>
                                <td>$ <?= $value["saldoAtual"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <td colspan="3" style="text-align: right">Total</td>
                            <td>$ <?= $total; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </main>

<?php include_once ("bottom.php"); ?>