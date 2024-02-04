<?php 
    require_once "scripts_sql.php";
    require_once ("header.php"); 

    $crud = new CRUD();
    $movimentos = $crud->indexTable();

    $resultado = 0;
?>
     
    <main class="container">
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
                        <td><?= $mov["dataMovimento"]; ?></td>
                        <td><?= $mov["nomeMovimento"]; ?></td>
                        <td><?= $mov["categoria"]; ?></td>
                        <td>$ <?= ($mov["tipo"] == "R" ? "+ " : "- ") . $mov["valor"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <td colspan="3" style="text-align: right">Resultado (Receitas - Despesas)</td>
                    <td>$ <?= $resultado; ?></td>
                </tr>
            </tfoot>
        </table>
    </main>
</body>
</html>