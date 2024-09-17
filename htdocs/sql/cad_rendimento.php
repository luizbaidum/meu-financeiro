<?php
    require 'scripts_sql.php';

    if (!empty($_POST)) {
        $crud = new CRUD();

        if (isset($_POST["cadRendimento"])) {
            unset($_POST["cadRendimento"]);

            if ($_POST["tipo"] == "1")
                $_POST["valorRendimento"] = ($_POST["valorRendimento"] * -1);

            $crud->insert("rendimento", $_POST);

            $saldo_atual = $crud->getSaldoAtual('conta_investimento', $_POST["idContaInvest"]);
            $item = [
                "saldoAtual"    => ($saldo_atual + $_POST["valorRendimento"]),
                "saldoAnterior" => $saldo_atual,
                "dataAnterior"  => date("Y-m-d")
            ];
            $item_where = [
                "idContaInvest" => $_POST["idContaInvest"]
            ];

            $crud->update("conta_investimento", $item, $item_where);

            $objetivos = $crud->selectAll('obj', [['idContaInvest', '=', $_POST['idContaInvest']]], [], []);

            foreach ($objetivos as $value) {
                $item = [
                    'saldoAtual' => $value['saldoAtual'] + ($_POST['valorRendimento'] * ($value['percentObjContaInvest'] / 100))
                ];
                $item_where = ['idObj' => $value['idObj']];
                $crud->update('obj', $item, $item_where);
            }
        }
    }

    header('location: ../public/contas_investimentos_index.php');