<?php 
    require_once 'header.php'; 

    $crud = new CRUD();
?>

<main class="container">
    <div class="card mt-2">
        <div class="card-header">
            Cadastrar Objetivo
        </div>
        <div class="card-body p-1">
            <form action="../sql/cad_objetivos.php" method="post">
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

<?php require_once 'bottom.php'; ?>
