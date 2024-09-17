<?php 
    include_once 'scripts_sql.php';

    if (!empty($_GET)) {
        $crud = new Crud();

        $crud->delete($_GET);
    }
?>