<?php 
    require 'Entidades.php';

    if (!empty($_POST)) {
        $obj = new Objetivos();
        $obj->insert();

        header('location: ../public/objetivos.php');
    }