<?php 
    include_once 'scripts_sql.php';
    include '../public/NumbersHelper.php';

    if (!empty($_POST)) {
        $crud = new Crud();

        $action = $_POST['action'];
        $where = array(
            'idMovimento' => $_POST['idMovimento']
        );

        unset($_POST['action']);
        unset($_POST['idMovimento']);

        $values = $_POST;

        $crud->update($action, $values, $where);
    }
?>