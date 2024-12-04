<?php 
    include_once 'scripts_sql.php';

    if (!empty($_GET)) {
        $crud = new Crud();

        $action = $_GET['action'];
        unset($_GET['action']);

        switch ($action) {
            case 'consultar-objetivos':
                $ret = $crud->selectAll($_GET['table'], [[$_GET['fieldId'], '=', $_GET['id']]], [], []);
                echo json_encode($ret);
                break;
        }

        exit;
    }
?>