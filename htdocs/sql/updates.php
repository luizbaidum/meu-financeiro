<?php 
    include_once 'scripts_sql.php';
    include '../public/NumbersHelper.php';

    if (!empty($_POST)) {
        $crud = new Crud();

        $action = $_POST['action'];
        $where = array(
            $_POST['nameIdPrincipal'] => $_POST['valueIdPrincipal']
        );

        if ($_POST['nameIdPrincipal'] == 'idMovimento') {
            $_POST['idCategoria'] = explode(' - sinal', $_POST['idCategoria'])[0];
        }

        unset($_POST['action']);
        unset($_POST['nameIdPrincipal']);
        unset($_POST['valueIdPrincipal']);

        $values = $_POST;

        $crud->update($action, $values, $where);
    }
?>