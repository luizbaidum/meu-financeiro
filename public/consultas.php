<?php 
    include_once 'scripts_sql.php';

    if (!empty($_GET)) {
        $crud = new Crud();

        $ret = $crud->selectAll($_GET['table'], [[$_GET['fieldId'], '=', $_GET['id']]], [], []);

        echo json_encode($ret[0]);

        exit;
    }
?>