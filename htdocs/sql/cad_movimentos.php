<?php 
    require 'scripts_sql.php';

    if (!empty($_POST)) {

        $crud = new CRUD();

        $APLICACAO = '12';
        $RESGATE = '10';

        $arr_cat = explode(' - sinal: ' , $_POST['idCategoria']);
        $_POST['idCategoria'] = $arr_cat[0];
        $sinal = $arr_cat[1];

        if (($_POST['idCategoria'] == $APLICACAO || $_POST['idCategoria'] == $RESGATE) && empty($_POST['idContaInvest'])) {
            echo '<div class="text-danger text-center">É obrigatório escolher uma conta investimento.</div>';
            exit;

            if ($_POST['idCategoria'] == $RESGATE && empty($_POST['idObjetivo'])) {
                echo '<div class="text-danger text-center">É obrigatório escolher um objetivo.</div>';
                exit;
            }

            if ($_POST['idCategoria'] == $RESGATE) {
                $validador = $crud->validarObjetivoComConta($_POST['idObjetivo'], $_POST['idContaInvest']);
                if (!$validador) {
                    echo '<div class="text-danger text-center">O objetivo escolhido não percente a conta investimento.</div>';
                    exit;
                }
            }
        }

        $id_conta_invest = $_POST['idContaInvest'] ?? '';
        $id_objetivo = $_POST['idObjetivo'] ?? '';
        unset($_POST['idContaInvest']);
        unset($_POST['idObjetivo']);

        //Inserção de Movimento
        $_POST['valor'] = $sinal . $_POST['valor'];
        $crud->insert('movimento', $_POST);

        //Inserção de Rendimento (invest ou retirada)
        if (!empty($id_conta_invest)) {
            switch ($_POST['idCategoria']) {
                case $APLICACAO:
                    $tipo = 4;
                    $valor_aplicado = ($_POST['valor'] * -1); //veio negativo, pois aplicação é saída de dinheiro da conta corrente, mas é entrada em aplicações.

                    $objetivos = $crud->selectAll('obj', [['idContaInvest', '=', $id_conta_invest]], [], []);

                    foreach ($objetivos as $value) {
                        $item = [
                            'saldoAtual' => $value['saldoAtual'] + ($valor_aplicado * ($value['percentObjContaInvest'] / 100))
                        ];
                        $item_where = ['idObj' => $value['idObj']];
                        $crud->update('obj', $item, $item_where);
                    }

                    break;
                case $RESGATE:
                    $tipo = 3;
                    $valor_aplicado = ($_POST['valor'] * -1); //veio positivo, pois resgate é entrada de dinheiro da conta corrente, mas é saída em aplicações.

                    $saldo_atual = $crud->getSaldoAtual('obj', $id_objetivo);
                    $item = [
                        'saldoAtual' => ($saldo_atual + $valor_aplicado)
                    ];
                    $item_where = [
                        'idObj' => $id_objetivo
                    ];
                    $crud->update('obj', $item, $item_where);

                    break;
                default:
                    $tipo = '';
            }

            $item = [
                'idContaInvest'   => $id_conta_invest,
                'valorRendimento' => $valor_aplicado,
                'dataRendimento'  => $_POST['dataMovimento'],
                'tipo'            => $tipo
            ];

            $crud->insert('rendimento', $item);

            $saldo_atual = $crud->getSaldoAtual('conta_investimento', $id_conta_invest);
            $item = [
                'saldoAtual' => ($saldo_atual + $valor_aplicado)
            ];
            $item_where = [
                'idContaInvest' => $id_conta_invest
            ];
            $crud->update('conta_investimento', $item, $item_where);
        }
    }

    header('location: ../public/movimentos.php');
?>