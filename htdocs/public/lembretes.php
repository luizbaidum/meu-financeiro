
<?php
    require_once ("header.php"); 

    if (!empty($_POST)) {
        $crud = new CRUD();
        $crud->insert("lembrete", $_POST);
    }
?>
    <main class="container">
        <div class="card p-1">
            <form action="lembretes.php" method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12">
                            <label for="idLembrete">Lembrete</label>
                            <input type="text" class="form-control" id="idLembrete" name="lembrete" required>
                        </div>
                    </div>
                    <input type="hidden" value=<?= date("Y-m-d"); ?> name="dataLembrete">
                </div>
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </form>
        </div>
    </main>

<?php include_once ("bottom.php"); ?>