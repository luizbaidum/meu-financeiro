<?php 
    require 'header.php';

    $crud = new CRUD();
    $orcamentos = $crud->orcamentos($_POST['mesFiltro'] ?? '');

    $total_orcado = 0;
?>

<main class="container">
	<form action="orcamento_index.php" data-method="post" id="idFormMesFiltro">
        <?php require_once 'select_month.php'; ?>
	</form>

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
</main>

<?php require 'bottom.php'; ?>