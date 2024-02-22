<?php 
    require_once "scripts_sql.php";
    require_once ("header.php");

    $crud = new CRUD();

    if (!empty($_POST) && isset($_POST["dataOrcamento"]))
        $crud->insert("orcamento", $_POST);

    $mes_selecionado = $_POST["mes"] ?? "0";
    $total_orcado = 0;

    $orcamentos = $crud->orcamentos($mes_selecionado);

    $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
?>

<main class="container">
	<form action="orcamento.php" method="post">
		<div class="form-group m-2">
			<div class="row">
				<div class="col-6">
					<label for="idMesOrcamentos">Month</label>
					<select class="form-select" id="idMesOrcamentos" name="idMesOrcamentos">
                        <option value="0">Todos</option>
                        <?php foreach ($months as $k => $v): ?>
                            <option value="<?= ($k + 1); ?>"<?= (($k + 1) == $mes_selecionado ? "selected" : ""); ?>><?= $v; ?></option>
                        <?php endforeach;?>
					</select>
				</div>
			</div>
		</div>
	</form>
	<div class="card p-1">
        <div class="card">
            <div class="card-header">Cadastro</div>
            <div class="card-body">
                <form action="orcamento.php" method="post">
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
        <div class="card mt-2">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Mês</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Valor Orçado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orcamentos as $value): 
                            if ($value["tipo"] == "R")
                                $total_orcado += $value["totalOrcado"];
                            else
                                $total_orcado -= $value["totalOrcado"];
                        ?>
                            <tr>
                                <td><?= $value["mesOrcado"]; ?></td>
                                <td><?= $value["categoria"]; ?></td>
                                <td><?= ($value["tipo"] == "R" ? "+ " : "- ") . $value["totalOrcado"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total</td>
                            <td><?= $total_orcado; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
	</div>
</main>

<?php include_once ("bottom.php"); ?>