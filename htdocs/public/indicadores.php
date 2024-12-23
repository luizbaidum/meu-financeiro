<?php 
    require_once 'header.php'; 

    $crud = new CRUD();

    if (isset($_POST['mesFiltro']) && !empty($_POST['mesFiltro'])) {
		$indicadores = $crud->indicadores($_POST['mesFiltro']); 
		$orcamentos = $crud->orcamentos($_POST['mesFiltro']);
	} else {
		$indicadores = $crud->indicadores(); 
		$orcamentos = $crud->orcamentos();
	}

	$receitas = 0;
	$aplicado = 0;
	foreach ($indicadores as $value) {
		if ($value['tipo'] == 'R' && $value['idCategoria'] != 10) //'Devolução de Aplicação'
			$receitas += $value['total'];
		
		if ($value['idCategoria'] == 12 || $value['idCategoria'] == 10) //'Aplicação' //'Devolução de Aplicação'
			$aplicado += $value['total'];
	}	
?>

<main class="container">
	<div class="card p-1">
		<div class="row card-body">
			<div class="col-12">
				Total Receitas: <?= NumbersHelper::formatUStoBR($receitas); ?>
			</div>
			<div class="col-12">
				Total Aplicado: <?= NumbersHelper::formatUStoBR($aplicado); ?>
			</div>
		</div>
		<div class="row">
			<?php foreach ($indicadores as $id => $value): ?>
				<div class="col-6 mb-2">
					<div class="card">
						<div class="card-header">
							<?= $value['categoria']; ?>
						</div>
						<div class="card-body">
							<div>
								<?= NumbersHelper::formatUStoBR($value['total']); ?>
							</div>
							<?php if (isset($orcamentos[$id])): ?>
								<small>Orçado: R$ <?= NumbersHelper::formatUStoBR($orcamentos[$id]['totalOrcado']); ?></small>
							<?php else: ?>
								<small>Orçado: R$ 0,00</small>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</main>

<?php require 'bottom.php'; ?>