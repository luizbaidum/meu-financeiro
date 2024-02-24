
<?php
    require_once "scripts_sql.php";
    $crud = new CRUD();

    if (!empty($_POST))
        $crud->insert("movimento", $_POST);
?>

<?php require_once ("header.php"); ?>
    <main class="container">
        <div class="card p-1">
            <form action="movimentos.php" method="post">
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
                            <select class="form-select" id="idCategoria" name="idCategoria" required>
                                <option value="">Selecione</option>
                                <?php 
                                    $categorias = $crud->selectAll("categoria", [], [], ["categoria" => "ASC"]);
                                    foreach ($categorias as $cat):
                                ?>
                                    <option value="<?= $cat["idCategoria"]; ?>"><?= $cat["categoria"] . " - " . $cat["tipo"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="tipo">Valor (EUA e apenas inteiro)</label>
                                <input type="number" class="form-control" id="idValor" name="valor" required>
                            </div>
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