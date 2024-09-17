<?php 
    require_once ("header.php"); 

    $crud = new CRUD();

    if (!empty($_POST)) {
        $crud->insert('erros', $_POST);
    }

    $erros = $crud->selectAll('erros', [], [], ['dataCod' => 'DESC']);
?>
     
    <main class="container">

        <div class="card mt-2 mb-2">
            <div class="card-header">
                <button class="btn btn-light btn-sm nav-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInsertErro" aria-expanded="false" aria-controls="collapseInsertErro">
                    Informar Erro
                </button>
            </div>
            <div class="collapse" id="collapseInsertErro">
                <div class="card-body p-1">
                    <form action="erros_do_bog.php" method="post">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="idDataCod">Data Código</label>
                                    <input type="date" class="form-control" id="idDataCod" name="dataCod" required>
                                </div>
                                <div class="col-6">
                                    <label for="idDataIdent">Data Identificado</label>
                                    <input type="date" class="form-control" id="idDataIdent" name="dataIdent" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="idDescricao">Descrição Erro</label>
                                        <input type="text" class="form-control" id="idDescricao" name="descricao" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="idMotivo">Motivo</label>
                                        <input type="text" class="form-control" id="idMotivo" name="motivo" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-1">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <table class="table">
            <theader>
                <tr>
                    <th>Data código</th>
                    <th>Data identificado</th>
                    <th>Descrição</th>
                    <th>Motivo</th>
                </tr>
            </theader>
            <tbody>
                <?php 
                    foreach ($erros as $mov): ?>
                    <tr>
                        <td><?= $mov["dataCod"]; ?></td>
                        <td><?= $mov["dataIdent"]; ?></td>
                        <td><?= $mov["descricao"]; ?></td>
                        <td><?= $mov["motivo"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

<?php include_once ("bottom.php"); ?>