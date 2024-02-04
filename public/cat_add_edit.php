
<?php
    if (!empty($_POST)) {
        require_once "scripts_sql.php";

        $_POST["tipo"] = strtoupper($_POST["tipo"]);

        if ($_POST["tipo"] != "R" && $_POST["tipo"] != "D"): ?>
            <div class="m-2 bg-light">
                <p class="text-danger">Atenção: Definir tipo como R ou D.</p>
            </div>
        <?php else:
            $crud = new CRUD();
            $crud->insert("categoria", $_POST);
        endif;
    }
?>

<?php require_once ("header.php"); ?>
    <main>
        <div class="card p-1">
            <form action="cat_add_edit.php" method="post">
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
                                <input type="text" class="form-control" id="tipo" placeholder="(R ou D)" name="tipo" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </form>
        </div>
    </main>
</body>
</html>