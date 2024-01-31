
<?php
    if (!empty($_POST)) {
        require_once "scripts_sql.php";

        if (true): ?>
            
        <?php else:
            //$crud = new CRUD();
            //$crud->insert("add_categoria", $_POST);
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
                            <label for="categoria">Nome Movimento</label>
                            <input type="text" class="form-control" id="categoria" name="movimento" required>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="categoria">Data Movimento</label>
                            <input type="text" class="form-control" id="categoria" name="dataMovimento" required>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="categoria">Categoria</label>
                            <input type="text" class="form-control" id="categoria" name="categoriaMovimento" required>
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