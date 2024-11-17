<?php 
if (!isset($_GET['idContaInvest']) || (isset($_GET['idContaInvest']) && $_GET['idContaInvest'] == '')) {
    echo '<div class="text-danger text-center">Acesso não permitido</div>';
    exit;
}

require_once 'header.php';

$crud = new CRUD();
$id_conta_invest = $_GET['idContaInvest'];

$objetivos = $crud->selectAll('obj', [['idContaInvest', '=', $id_conta_invest]], [], []);
$conta = $crud->selectAll('conta_investimento', [['idContaInvest', '=', $id_conta_invest]], [], []);

$saldo_obj = 0;
?>

<main class="container">
    <div class="card mt-2">
        <div class="card-header">
            Objetivos do investimento conta <?= $conta[0]['nomeBanco'] . ' - ' . $conta[0]['tituloInvest']; ?>
            <br>
            <small>Saldo conta: <?= $conta[0]['saldoAtual']; ?></small> <br>
        </div>
        <div class="card-body">
            <div class="row">
                <?php 
                    foreach ($objetivos as $item): 
                        $saldo_obj += $item['saldoAtual'];
                        $id = $item['idObj'];
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <form data-url-action="../sql/updates.php" data-method="POST" class="form-ajax">

                                    <input type="hidden" name="action" value="obj">
                                    <input type="hidden" name="idContaInvest" value="<?= $item['idContaInvest']; ?>">
                                    <input type="hidden" name="percentObjContaInvestOLD" value="<?= $item['percentObjContaInvest']; ?>">

                                    <div class="form-group">
                                        <label for="idObj">id</label>
                                        <input type="text" class="form-control" name="idObj" id="idObj" value="<?= $item['idObj']; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="idNomeObj-<?= $id; ?>">Nome</label>
                                        <input type="text" class="form-control" name="nomeObj" id="idNomeObj-<?= $id; ?>" value="<?= $item['nomeObj']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="idVlrObj-<?= $id; ?>">Valor obj.</label>
                                        <input type="text" class="form-control" name="vlrObj" id="idVlrObj-<?= $id; ?>" value="<?= $item['vlrObj']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="idPercentObjContaInvest-<?= $id; ?>">% destinado ao obj.</label>
                                        <input type="text" class="form-control" name="percentObjContaInvest" id="idPercentObjContaInvest-<?= $id; ?>" value="<?= $item['percentObjContaInvest']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="idSaldoAtual">Saldo atual</label>
                                        <input type="text" class="form-control" id="idSaldoAtual" value="<?= $item['saldoAtual']; ?>" disabled>
                                    </div>

                                    <div class="row px-3 mt-2">
                                        <button type="submit" class="btn btn-success mt-1">Salvar mudanças</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <small>Saldo objetivos: <?= $saldo_obj; ?></small>
        </div>
    </div>
</main>

<?php require 'bottom.php'; ?>