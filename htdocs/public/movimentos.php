
<?php
    require_once 'header.php';

    $crud = new CRUD();
    $url_to_submit = '../sql/cad_movimentos.php';
    
    $options_list = json_encode($crud->selectAll('obj', [], [], []));
    echo '<script>var options_obj = ' . $options_list . '</script>';

    $movimento = [];
    if (isset($_GET['idMovimento'])) {
        $movimento = $crud->selectAll('movimento', [['idMovimento', '=', $_GET['idMovimento']]], [], [])[0];
        $url_to_submit = '../sql/updates.php';
    }
?>

    <main class="container">
        <div class="card p-1">
            <form action="<?= $url_to_submit; ?>" method="post">

            <?php if (isset($_GET['idMovimento'])) { ?>
                <input type="hidden" name="action" value="movimento">
                <input type="hidden" name="idMovimento" value="<?= $_GET['idMovimento']; ?>">
            <?php } ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label for="idNomeMovimento">Nome Movimento</label>
                            <input type="text" class="form-control" id="idNomeMovimento" name="nomeMovimento" required value="<?= $movimento['nomeMovimento'] ?? ''; ?>">
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="idDataMovimento">Data Movimento (m/d/a)</label>
                            <input type="date" class="form-control" id="idDataMovimento" name="dataMovimento" required value="<?= $movimento['dataMovimento'] ?? ''; ?>">
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="idCategoria">Categoria</label>
                            <select class="form-select select-categoria" id="idCategoria" name="idCategoria" required>
                                <option value="">Selecione</option>
                                <?php 
                                    $categorias = $crud->selectAll('categoria', [], [], ['tipo' => 'ASC', 'categoria' => 'ASC']);
                                    foreach ($categorias as $cat):
                                ?>
                                    <?php if (!empty($movimento) && $movimento['idCategoria'] == $cat['idCategoria']): ?>
                                        <option value="<?= $cat['idCategoria'] . ' - sinal: ' . $cat['sinal']; ?>" selected><?= $cat['categoria'] . ' - ' . $cat['tipo']; ?></option>
                                    <?php else: ?>
                                        <option value="<?= $cat['idCategoria'] . ' - sinal: ' . $cat['sinal']; ?>"><?= $cat['categoria'] . ' - ' . $cat['tipo']; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-3">
                                <label for="tipo">Valor (EUA e apenas inteiro)</label>
                                <input type="number" class="form-control" id="idValor" name="valor" required value="<?= $movimento['valor'] ?? ''; ?>">
                            </div>
                            <div class="col-3">
                                <label for="idProprietario">Proprietário</label>
                                <select class="form-select" id="idProprietario" name="proprietario">
                                    <option value="1" <?= (!empty($movimento) && $movimento['proprietario'] == '1' ? ' selected ' : ''); ?>>Luiz</option>
                                    <option value="2" <?= (!empty($movimento) && $movimento['proprietario'] == '2' ? ' selected ' : ''); ?>>Uepa</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="idContaInvest">Conta Invest (se houver)</label>
                                <select class="form-select" id="idContaInvest" name="idContaInvest">
                                    <option value="">Selecione</option>
                                    <?php 
                                        $invests = $crud->selectAll('conta_investimento', [], [], ['nomeBanco' => 'ASC']);
                                        foreach ($invests as $value):
                                    ?>
                                        <?php if (!empty($movimento) && $movimento['idContaInvest'] == $value['idContaInvest']): ?>
                                            <option value="<?= $value['idContaInvest']; ?>" selected><?= $value['nomeBanco'] . ' - ' . $value['tituloInvest']; ?></option>
                                        <?php else: ?>
                                            <option value="<?= $value['idContaInvest']; ?>"><?= $value['nomeBanco'] . ' - ' . $value['tituloInvest']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div id="content-obj" class="col-3"></div>
                           <!-- <div class="col-6 mt-4">
                                <label for="tipo">No Cartão?</label>
                                <input type="checkbox" id="idCartao" name="cartao" value="1">
                            </div> -->
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </form>
        </div>
    </main>

<?php include_once ("bottom.php"); ?>