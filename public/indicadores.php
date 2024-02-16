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
		<pre>
			<?php 
			    print_r($indicadores); 
			?>
		</pre>
	</div>
</main>

<?php include_once ("bottom.php"); ?>