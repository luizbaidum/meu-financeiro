
<?php
    require 'header.php'; 
?>
    <main class="container">
        <div class="card p-1">
            <form action="../sql/cad_lembretes.php" method="post">
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

<?php require 'bottom.php'; ?>