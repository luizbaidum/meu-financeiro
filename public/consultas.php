<?php 
    include_once 'scripts_sql.php';

    if (!empty($_GET)) {
        $crud = new Crud();

        $action = $_GET['action'];
        $ret = $crud->selectAll($action, [[$_GET['campo'], '=', $_GET['id']]], [], []);

        echo json_encode($ret);
    }
?>