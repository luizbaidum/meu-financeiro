<?php 
    require 'scripts_sql.php';

    if (!empty($_POST)) {
        $crud = new CRUD();

        if (isset($_POST["cadContaInvest"])) {
            unset($_POST["cadContaInvest"]);

            $_POST["saldoAtual"] = $_POST["saldoInicial"];
            $crud->insert("conta_investimento", $_POST);
        }
    }

    header('location: ../public/contas_investimentos.php');