<?php 
    require 'Entidades.php';

    if (!empty($_GET)) {
        $crud = new Crud();

        switch ($_GET['action']) {
            case 'lembrete':
                $ret = $crud->delete($_GET);
                break;
            case 'movimento':
                $ret = (new Movimento())->delete();
                break;
            case 'orcamento':
                $ret = (new Orcamento())->delete();
                break;
            default:
                $ret = false;
                $txt = 'Deleção não realizada.';
        }

        if ($ret) {
            $crud->enviarRetorno(true, 'Deleção realizada com sucesso.');
        }

        $crud->enviarRetorno(false, 'Deleção não realizada.');
    }
?>