<?php

//criar cronjobs, se possivel neste servidor, para pegar as despesas mensais e lancar mes a mes a previsao de pgto.
//se nao der certo, fazer um modelo onde lista e select box onde escolho quais despesas quero lancar no mes. (até melhor, pois deve permitir eu editar o dia e o valor)

    require_once ("header.php"); 

    $crud = new CRUD();

    if (!empty($_POST)) { 
        if (isset($_POST['cadastro']) && $_POST['cadastro'] === 'T') {

        }

        if (isset($_POST['registro']) && $_POST['registro'] === 'T') {
            
        }
    }
?>

<main class="container">
    <div class="card mt-2">
        <div class="card-header">
            <button class="btn btn-light btn-sm nav-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCadDespMensais" aria-expanded="false" aria-controls="collapseCadDespMensais">
                Cadastrar Movimento Mensal
            </button>
        </div>
        <div class="collapse" id="collapseCadDespMensais">
            <div class="card-body p-1">
                <form action="movimentos_mensais.php" method="post">
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
                            <div class="col-12">
                                <label for="idNomeMovimento">Nome Movimento</label>
                                <input type="text" class="form-control" id="idNomeMovimento" name="nomeMovimento" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once ("bottom.php"); ?>
