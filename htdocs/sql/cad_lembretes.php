<?php
    require 'scripts_sql.php'; 

    if (!empty($_POST)) {
        $crud = new CRUD();
        $crud->insert("lembrete", $_POST);
    }

    header('location: ../public/lembretes.php');
