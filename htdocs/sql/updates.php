<?php 
    require 'Entidades.php';

    if (!empty($_POST)) {

        $action = $_POST['action'];

        if ($action == 'movimento') {
            $obj = new Movimento();
            $obj->update();

            header('location: ../public/index.php');
        }
        
        if ($action == 'obj') {
            $obj = new Objetivos();
            $obj->update();
        }
    }
?>