<?php
    require_once ("header.php"); 

    $crud = new CRUD();

    $arr_mensais = $crud->getMensais();

    if (!empty($_POST)) { 
        if (isset($_POST['cadastro']) && $_POST['cadastro'] === 'T') {
            unset($_POST['cadastro']);
            $crud->insert('mensais', $_POST);
        }

        if (isset($_POST['registro']) && $_POST['registro'] === 'T') {
            $item = array();

            foreach ($_POST['idMovMensal'] as $id) {
                $arr_cat = explode(' - sinal: ', $_POST['idCategoria'][$id]);
                $sinal = $arr_cat[1];

                $item['nomeMovimento'] = $_POST['nomeMovimento'][$id];
                $item['dataMovimento'] = $_POST['dataMovimento'][$id];
                $item['idCategoria'] = $arr_cat[0];
                $item['valor'] = $sinal . $_POST['valor'][$id];

                $crud->insert('movimento', $item);
            }
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

    <div class="card mt-2 p-1">
        <form action="movimentos_mensais.php" method="post">
            <input type="hidden" value="T" name="registro">
            <table class="table">
                <thead>
                    <tr>
                        <th>select</th>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Categoria</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php foreach ($arr_mensais as $value): ?>
                        <tr>
                            <td>
                                <input type="checkbox" value="<?= $value['idMovMensal']; ?>" name="idMovMensal[]">
                            </td>
                            <td>
                                <input type="date" id="idDataMovimento" name="dataMovimento[<?= $value['idMovMensal']; ?>]" value="<?= $value['dataRepete']; ?>">
                            </td>
                            <td>
                                <?= $value['nomeMovimento']; ?>
                                <input type="hidden" name="nomeMovimento[<?= $value['idMovMensal']; ?>]" value="<?= $value['nomeMovimento']; ?>">
                            </td>
                            <td>
                                <?= $value['valorDespesa']; ?>
                                <input type="hidden" name="valor[<?= $value['idMovMensal']; ?>]" value="<?= $value['valorDespesa']; ?>">
                            </td>
                            <td>
                                <?= $value['idCategoria'] . ' - ' . $value['categoria'] . ' - ' . $value['tipo']; ?>
                                <input type="hidden" name="idCategoria[<?= $value['idMovMensal']; ?>]" value="<?= $value["idCategoria"] . " - sinal: " . $value["sinal"]; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-1">Submit</button>
                </div>
            </div>
        </form>
    </div>
</main>

<?php include_once ("bottom.php"); ?>
