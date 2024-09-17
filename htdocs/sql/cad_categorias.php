<?php
    require 'scripts_sql.php';

    if (!empty($_POST)) {
        $_POST['tipo'] = strtoupper($_POST['tipo']);

        if ($_POST['tipo'] != 'R' && $_POST['tipo'] != 'D' && $_POST['tipo'] != 'A'): ?>
            <div class="m-2 bg-light">
                <p class="text-danger">Atenção: Definir tipo como R, D ou A.</p>
            </div>
        <?php elseif ($_POST['sinal'] != '+' && $_POST['sinal'] != '-'): ?>
            <div class="m-2 bg-light">
                <p class="text-danger">Atenção: Definir sinal como + ou -.</p>
            </div>
        <?php else:
            $crud = new CRUD(); 
            $crud->insert("categoria", $_POST);
        endif;
    }

    header('location: ../public/categorias.php');
?>