<?php
    require_once ("header.php");

    $crud = new CRUD();

    if (!empty($_POST)) {
        $id_conta_invest = $_POST['idContaInvest'];
        $percentual = $_POST['percentObjContaInvest'];

        $utilizado = $crud->validarPercentualDisponivel($id_conta_invest, $percentual);

        if ($utilizado) {
            echo '<div class="text-center"><span class="text-danger">Atenção!</span> A Conta Invest informada já está ' . $utilizado . '% comprometida.</div>';
        } else {
            $id_obj = $crud->insert('obj', $_POST);
            $crud->atualizarSaldoObj($id_obj, $percentual, $id_conta_invest);
        }        
    }

    print_r($_POST);
    print_r($_GET);

    if (isset($_GET['idContaConsultar'])) {
        $id_conta_invest = $_GET['idContaConsultar'];
        $objetivos = $crud->selectAll('obj', ['idContaInvest', '=', $id_conta_invest], [], []);

        print_r($objetivos);
    }
?>

<main class="container">
    <div class="card mt-2">
        <div class="card-header">
            Cadastrar Objetivo
        </div>
        <div class="card-body p-1">
            <form action="cad_objetivos.php" method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label for="idNomeObj">Título</label>
                            <input type="text" class="form-control" id="idNomeObj" name="nomeObj" required>
                        </div>
                        <div class="col-6">
                            <label for="idVlrObj">Valor Final Juntar (decimal com ponto)</label>
                            <input type="number" class="form-control" id="idVlrObj" name="vlrObj" step=".01" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label for="idContaInvest">Conta Invest</label>
                            <select class="form-select" id="idContaInvest" name="idContaInvest" required>
                                <option value="">Selecione</option>
                                <?php 
                                    $invests = $crud->selectAll("conta_investimento", [], [], ["nomeBanco" => "ASC", "tituloInvest" => "ASC"]);
                                    foreach ($invests as $value):
                                ?>
                                    <option value="<?= $value["idContaInvest"]; ?>"><?= $value["nomeBanco"] . " - " . $value["tituloInvest"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="idPercentObjContaInvest">% da Conta p/ Objetivo (decimal com ponto)</label>
                            <input type="number" class="form-control" id="idPercentObjContaInvest" name="percentObjContaInvest" step=".01" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </form>
        </div>
    </div>
</main>

<?php include_once ("bottom.php"); ?>
