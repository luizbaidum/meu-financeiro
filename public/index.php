<?php 
    require_once "scripts_sql.php";
    require_once ("header.php"); 

    $crud = new CRUD();

    if (isset($_POST["mes"]) && !empty($_POST["mes"]))
        $movimentos = $crud->indexTable($_POST["mes"]);
    else
        $movimentos = $crud->indexTable();

    $resultado = 0;
    $mes_selecionado = $_POST["mes"] ?? "0";

    $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
?>
     
    <main class="container">
        <form action="index.php" method="post">
            <div class="form-group m-2">
                <div class="row">
                    <div class="col-6">
                        <label for="idMes">Month</label>
                        <select class="form-select" id="idMes" name="idMes">
                            <option value="0">Todos</option>
                            <?php foreach ($months as $k => $v): ?>
                                <option value="<?= ($k + 1); ?>"<?= (($k + 1) == $mes_selecionado ? "selected" : ""); ?>><?= $v; ?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
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