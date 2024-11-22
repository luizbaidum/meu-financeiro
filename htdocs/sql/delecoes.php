<?php 
    require 'Entidades.php';

    if (!empty($_GET)) {
        $crud = new Crud();

        switch ($_GET['action']) {
            case 'lembrete':
                $crud->delete($_GET);
                break;
            case 'movimento':
                (new Movimento())->delete();
                break;
        }
    }
?>