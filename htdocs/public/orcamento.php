<?php 
    require 'header.php';

    $crud = new CRUD();

    $months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'); 
?>

<main class="container">
	<div class="card p-1">
        <div class="card">
            <div class="card-header">Importar</div>
            <div class="card-body">
                <form data-url-action="../sql/cad_orcamento.php" data-method="POST" class="form-ajax">
                    <div class="form-group">
                        <input type="hidden" name="buscarImportacao" value="1">
                        <div class="row">
                            <div class="col-6">
                                <label for="idOrigem">Mês origem</label>
                                <input type="month" id="idOrigem" name="origem" placeholder="yyyy-mm">
                            </div>
                            <div class="col-6">
                                <label for="idDestino">Mês destino</label>
                                <input type="month" id="idDestino" name="destino" placeholder="yyyy-mm">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">Buscar</button>
                </form>  
            </div>
        </div>

        <div class="content-importar"></div>

        <div class="card mt-2">
            <div class="card-header">Cadastro</div>
            <div class="card-body">
                <form action="../sql/cad_orcamento.php" method="post">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <label for="idDataOrcamento">Mês Movimento Orçado</label>
                                <select class="form-select" id="idDataOrcamento" name="dataOrcamento" required>
                                    <?php foreach ($months as $k => $v): ?>
                                        <option value="<?= date('y') . "-" . ($k + 1) . "-01"; ?>" <?= ((date('m') + 1) == ($k + 1) ? "selected" : ""); ?>><?= $v; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="idCategoria">Categoria Orçamento</label>
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
                            <div class="col-12 col-sm-4">
                                <label for="tipo">Valor (EUA e apenas inteiro)</label>
                                <input type="number" class="form-control" id="idValor" name="valor" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">Submit</button>
                </form>  
            </div>
        </div>
	</div>
</main>

<?php require 'bottom.php'; ?>