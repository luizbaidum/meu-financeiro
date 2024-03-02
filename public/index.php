<?php 
    require_once ("header.php"); 

    $crud = new CRUD();

    if (isset($_POST["mesFiltro"]) && !empty($_POST["mesFiltro"]))
        $movimentos = $crud->indexTable($_POST["mesFiltro"]);
    else
        $movimentos = $crud->indexTable();

    $resultado = 0;
?>
     
    <main class="container">
        <form action="index.php" method="post">
            <?php require_once "select_month.php"; ?>
        </form>

        <table class="table">
            <theader>
                <tr>
                    <th>Data</th>
                    <th>Movimento</th>
                    <th>Categoria</th>
                    <th>Valor</th>
                </tr>
            </theader>
            <tbody>
                <?php foreach ($movimentos as $mov): ?>
                    <?php
                        if ($mov["tipo"] == "R")
                            $resultado += $mov["valor"];
                        else 
                            $resultado -= $mov["valor"];
                    ?>
                    <tr>
                        <td class="data-movimento"><?= $mov["dataMovimento"]; ?></td>
                        <td><?= $mov["nomeMovimento"]; ?></td>
                        <td><?= $mov["categoria"]; ?></td>
                        <td>$ <?= ($mov["tipo"] == "R" ? "+ " : "- ") . $mov["valor"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <td colspan="3" style="text-align: right">Resultado (Rec. - Des. - Apli.)</td>
                    <td>$ <?= $resultado; ?></td>
                </tr>
            </tfoot>
        </table>
    </main>

<?php include_once ("bottom.php"); ?>