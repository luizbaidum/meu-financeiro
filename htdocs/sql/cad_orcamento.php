<?php 
    require 'scripts_sql.php';

    $crud = new CRUD();

    if (!empty($_POST) && isset($_POST["dataOrcamento"]))
        $crud->insert("orcamento", $_POST);

    header('location: ../public/orcamento.php');

