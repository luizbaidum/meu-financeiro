
<?php require 'header.php'; ?>

<main class="container">
    <div class="card">
        <div class="card-header">
            Cadastrar Conta Invest
        </div>
        <div class="card-body p-1">
            <form action="../sql/cad_contas_investimentos.php" method="post">
                <input type="hidden" name="cadContaInvest" value="1">
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
                            <div class="col-6">
                                <label for="idSaldoInicial">Saldo de início (decimal com ponto)</label>
                                <input type="number" class="form-control" id="idSaldoInicial" name="saldoInicial" step=".01" required>
                            </div>
                            <div class="col-6">
                                <label for="idProprietario">Proprietário</label>
                                <select class="form-select" id="idProprietario" name="proprietario">
                                    <option value="1">Luiz</option>
                                    <option value="2">Uepa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </form>
        </div>
    </div>
</main>

<?php require 'bottom.php'; ?>