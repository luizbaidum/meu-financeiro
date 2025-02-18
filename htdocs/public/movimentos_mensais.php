<?php
    require 'header.php';

    $crud = new CRUD();
?>

<main class="container">
    <div class="card mt-2">
        <div class="card-header">
            Cadastrar Movimento Mensal
        </div>
        <div class="card-body p-1">
            <form action="../sql/cad_movimentos_mensais.php" method="post">
                <input type="hidden" name="cadastro" value="T">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <label for="idDataRepete">Data Repetição (mensal)</label>
                            <input type="date" class="form-control" id="idDataRepete" name="dataRepete" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="idValorDespesa">Valor (decimal com ponto)</label>
                            <input type="number" class="form-control" id="idValorDespesa" name="valorDespesa" step=".01" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="idCategoria">Categoria</label>
                            <select class="form-select" id="idCategoria" name="idCategoria" required>
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
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label for="idNomeMovimento">Nome Movimento</label>
                            <input type="text" class="form-control" id="idNomeMovimento" name="nomeMovimento" required>
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
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </form>
        </div>
    </div>
</main>

<?php require 'bottom.php'; ?>
