<?php 
    require_once "scripts_sql.php";
    require_once ("header.php"); 

    $crud = new CRUD();

    if (isset($_POST["mes"]) && !empty($_POST["mes"]))
        $indicadores = $crud->indicadores($_POST["mes"]);
    else
        $indicadores = $crud->indicadores();

    $mes_selecionado = $_POST["mes"] ?? "0";

    $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

	$receitas = 0;
	$aplicado = 0;
	foreach ($indicadores as $value) {
		if ($value["tipo"] == "R" && $value["categoria"] != "Devolução de Aplicação")
			$receitas += $value["total"];
		
		if ($value["categoria"] == "Aplicação")
			$aplicado += $value["total"];
		elseif ($value["categoria"] == "Devolução de Aplicação")
			$aplicado -= $value["total"];
	}	
?>

<main class="container">
	<form action="indicadores.php" method="post">
		<div class="form-group m-2">
			<div class="row">
				<div class="col-6">
					<label for="idMesIndicadores">Month</label>
					<select class="form-select" id="idMesIndicadores" name="idMesIndicadores">
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
		<div class="row card-body">
			<div class="col-12">
				Total Receitas: <?= $receitas; ?>
			</div>
			<div class="col-12">
				Total Aplicado: <?= $aplicado; ?>
			</div>
		</div>
		<div class="row">
			<?php foreach ($indicadores as $value): ?>
				<div class="col-6">
					<div class="card">
						<div class="card-header">
							<?= $value["categoria"]; ?>
						</div>
						<div class="card-body">
							<?= ($value["tipo"] == "R" ? "+ " : "- ") . $value["total"]; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</main>

<?php include_once ("bottom.php"); ?>