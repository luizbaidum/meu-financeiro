<?php 
    $crud = new CRUD();

    if (!empty($_POST)) {
        $id_conta_invest = $_POST['idContaInvest'];
        $percentual = $_POST['percentObjContaInvest'];

        $utilizado = $crud->validarPercentualDisponivel($id_conta_invest, $percentual);

        if ($utilizado) {
            echo '<div class="text-center"><span class="text-danger">Atenção!</span> A Conta Invest informada já está ' . $utilizado . '% comprometida.</div>';
        } else {
            $id_obj = $crud->insert('obj', $_POST);
            $crud->atualizarSaldoObj($id_obj, $percentual, $id_conta_invest);
        }        
    }

    if (isset($_GET['idContaConsultar'])) {
        $id_conta_invest = $_GET['idContaConsultar'];
        $objetivos = $crud->selectAll('obj', ['idContaInvest', '=', $id_conta_invest], [], []);
    }