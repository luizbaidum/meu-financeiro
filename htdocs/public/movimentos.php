
<?php
    require_once 'header.php';

    $crud = new CRUD();
    
    $options_list = json_encode($crud->selectAll('obj', [], [], []));
    echo '<script>var options_obj = ' . $options_list . '</script>';
?>

    <main class="container">
        <div class="card p-1">
            <form action="../sql/cad_movimentos.php" method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label for="idNomeMovimento">Nome Movimento</label>
                            <input type="text" class="form-control" id="idNomeMovimento" name="nomeMovimento" required>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="idDataMovimento">Data Movimento (m/d/a)</label>
                            <input type="date" class="form-control" id="idDataMovimento" name="dataMovimento" required>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="idCategoria">Categoria</label>
                            <select class="form-select select-categoria" id="idCategoria" name="idCategoria" required>
                                <option value="">Selecione</option>
                                <?php 
                                    $categorias = $crud->selectAll("categoria", [], [], ["tipo" => "ASC", "categoria" => "ASC"]);
                                    foreach ($categorias as $cat):
                                ?>
                                    <option value="<?= $cat["idCategoria"] . " - sinal: " . $cat["sinal"]; ?>"><?= $cat["categoria"] . " - " . $cat["tipo"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-3">
                                <label for="tipo">Valor (EUA e apenas inteiro)</label>
                                <input type="number" class="form-control" id="idValor" name="valor" required>
                            </div>
                            <div class="col-3">
                                <label for="idProprietario">Proprietário</label>
                                <select class="form-select" id="idProprietario" name="proprietario">
                                    <option value="1">Luiz</option>
                                    <option value="2">Uepa</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="idContaInvest">Conta Invest (se houver)</label>
                                <select class="form-select" id="idContaInvest" name="idContaInvest">
                                    <option value="">Selecione</option>
                                    <?php 
                                        $invests = $crud->selectAll("conta_investimento", [], [], ["nomeBanco" => "ASC"]);
                                        foreach ($invests as $value):
                                    ?>
                                        <option value="<?= $value["idContaInvest"]; ?>"><?= $value["nomeBanco"] . " - " . $value["tituloInvest"]; ?></option>
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