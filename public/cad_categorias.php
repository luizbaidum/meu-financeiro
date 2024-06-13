
<?php
    require_once ("header.php");

    if (!empty($_POST)) {
        $_POST["tipo"] = strtoupper($_POST["tipo"]);

        if ($_POST["tipo"] != "R" && $_POST["tipo"] != "D" && $_POST["tipo"] != "A"): ?>
            <div class="m-2 bg-light">
                <p class="text-danger">Atenção: Definir tipo como R, D ou A.</p>
            </div>
        <?php elseif ($_POST["sinal"] != "+" && $_POST["sinal"] != "-"): ?>
            <div class="m-2 bg-light">
                <p class="text-danger">Atenção: Definir sinal como + ou -.</p>
            </div>
        <?php else:
            $crud = new CRUD(); 
            $crud->insert("categoria", $_POST);
        endif;
    }
?>

    <main class="container">
        <div class="card p-1">
            <form action="cad_categorias.php" method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label for="categoria">Nome Categoria</label>
                            <input type="text" class="form-control" id="categoria" name="categoria" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <label for="tipo">Tipo</label>
                                <input type="text" class="form-control" id="tipo" placeholder="(R, D ou A)" name="tipo" required>
                            </div>
                            <div class="col-12 col-sm-3">
                                <label for="tipo">Sinal</label>
                                <input type="text" class="form-control" id="sinal" placeholder="(+ ou -)" name="sinal" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </form>
        </div>
    </main>

<?php include_once ("bottom.php"); ?>