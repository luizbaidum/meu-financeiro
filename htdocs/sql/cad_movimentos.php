<?php 
    require 'Entidades.php';

    if (!empty($_POST)) {
        $obj = new Movimento();
        $obj->insert();

        header('location: ../public/movimentos.php');
    }
?>