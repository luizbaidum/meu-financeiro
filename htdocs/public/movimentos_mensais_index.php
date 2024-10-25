<?php
    require 'header.php'; 

    $crud = new CRUD();

    $arr_mensais = $crud->getMensais();
?>

<main class="container">
    <div class="card mt-2 p-1">
        <form action="../sql/cad_movimentos_mensais.php" method="post">
            <input type="hidden" value="T" name="registro">
            <table class="table">
                <thead>
                    <tr>
                        <th>selecione</th>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Prop.</th>
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
                                <?php   
                                    $dia_original = date('d', strtotime($value['dataRepete']));
                                    $mes_original = date('m', strtotime($value['dataRepete']));
                                    $ano_original = date('Y', strtotime($value['dataRepete']));
                                    $mes_atual = date('m');
                                    $ano_atual = date('Y');
                    
                                    $date = date_format(date_create("$ano_atual-$mes_atual-$dia_original"), 'Y-m-d');
                                ?>
                                <input type="date" id="idDataMovimento" name="dataMovimento[<?= $value['idMovMensal']; ?>]" value="<?= $date; ?>">
                            </td>
                            <td>
                                <?= $value['nomeMovimento']; ?>
                                <input type="hidden" name="nomeMovimento[<?= $value['idMovMensal']; ?>]" value="<?= $value['nomeMovimento']; ?>">
                            </td>
                            <td>
                                <select class="form-select" id="idProprietario" name="proprietario[<?= $value['idMovMensal']; ?>]">
                                    <option value="1">Luiz</option>
                                    <option value="2" <?= $value['proprietario'] == '2' ? ' selected ' : ''; ?>>Uepa</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control"  name="valor[<?= $value['idMovMensal']; ?>]" step=".01" value="<?= $value['valorDespesa']; ?>">
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

<?php require 'bottom.php'; ?>
