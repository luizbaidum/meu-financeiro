<?php
    require_once ("header.php"); 

    $crud = new CRUD();

    if (!empty($_POST)) { 
        if (isset($_POST['registro']) && $_POST['registro'] === 'T') {
            $item = array();

            foreach ($_POST['idMovMensal'] as $id) {
                $arr_cat = explode(' - sinal: ', $_POST['idCategoria'][$id]);
                $sinal = $arr_cat[1];

                $item['nomeMovimento'] = $_POST['nomeMovimento'][$id];
                $item['dataMovimento'] = $_POST['dataMovimento'][$id];
                $item['idCategoria'] = $arr_cat[0];
                $item['valor'] = $sinal . $_POST['valor'][$id];

                $crud->insert('movimento', $item);
            }
        }
    }

    $arr_mensais = $crud->getMensais();
?>

<main class="container">
    <div class="card mt-2 p-1">
        <form action="movimentos_mensais.php" method="post">
            <input type="hidden" value="T" name="registro">
            <table class="table">
                <thead>
                    <tr>
                        <th>select</th>
                        <th>Data</th>
                        <th>Descrição</th>
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

<?php include_once ("bottom.php"); ?>
