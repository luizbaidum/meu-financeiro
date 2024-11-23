<?php

require 'scripts_sql.php';

class Movimento
{
    private $APLICACAO = '12';
    private $RESGATE = '10';

    public function insert()
    {
        $crud = new CRUD();

        $arr_cat = explode(' - sinal: ' , $_POST['idCategoria']);
        $_POST['idCategoria'] = $arr_cat[0];
        $sinal = $arr_cat[1];

        $this->validations();

        $id_conta_invest = $_POST['idContaInvest'];
        $id_objetivo = $_POST['idObjetivo'] ?? '';
        unset($_POST['idObjetivo']);

        //Inserção de Movimento
        $_POST['valor'] = $sinal . $_POST['valor'];
        $id_movimento = $crud->insert('movimento', $_POST);

        //Inserção de Rendimento (invest ou retirada)
        if (!empty($id_conta_invest)) {
            switch ($_POST['idCategoria']) {
                case $this->APLICACAO:
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
                case $this->RESGATE:
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
                'tipo'            => $tipo,
                'idMovimento'     => $id_movimento
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

    private function validations()
    {
        if (($_POST['idCategoria'] == $this->APLICACAO || $_POST['idCategoria'] == $this->RESGATE) && empty($_POST['idContaInvest'])) {
            echo '<div class="text-danger text-center">É obrigatório escolher uma conta investimento.</div>';
            exit;

            if ($_POST['idCategoria'] == $this->RESGATE && empty($_POST['idObjetivo'])) {
                echo '<div class="text-danger text-center">É obrigatório escolher um objetivo.</div>';
                exit;
            }

            if ($_POST['idCategoria'] == $this->RESGATE) {
                $validador = $crud->validarObjetivoComConta($_POST['idObjetivo'], $_POST['idContaInvest']);
                if (!$validador) {
                    echo '<div class="text-danger text-center">O objetivo escolhido não percente a conta investimento.</div>';
                    exit;
                }
            }
        }
    }

    public function update()
    {
        $crud = new Crud();

        $id_movimento = $_POST['idMovimento'];
        $id_conta_invest = $_POST['idContaInvest'];
        $id_objetivo = $_POST['idObjetivo'] ?? '';
        $rendimento = $crud->selectAll('rendimento', [['idMovimento', '=', $_POST['idMovimento']]], [], []);

        $where = array(
            'idMovimento' => $_POST['idMovimento']
        );

        $arr_cat = explode(' - sinal: ' , $_POST['idCategoria']);
        $_POST['idCategoria'] = $arr_cat[0];
        $sinal = $arr_cat[1];

        if ($sinal == '-' && $_POST['valor'] > 0) {
            $_POST['valor'] = $_POST['valor'] * -1;
        } elseif ($sinal == '+' && $_POST['valor'] < 0) {
            $_POST['valor'] = $_POST['valor'] * -1;
        }

        unset($_POST['idMovimento']);
        unset($_POST['idObjetivo']);

        $values = $_POST;

        $crud->update('movimento', $values, $where);

        if (isset($rendimento[0]['idRendimento'])) {
            $old_id = $rendimento[0]['idRendimento'];
            $old_invest = $rendimento[0]['idContaInvest'];
            $old_valor = $rendimento[0]['valorRendimento'];
            $old_tipo = $rendimento[0]['tipo'];
            $old_data = $rendimento[0]['dataRendimento'];
            $old_movimento = $rendimento[0]['idMovimento'];

            $crud->delete([
                'action'       => 'rendimento',
                'idRendimento' => $old_id
            ]);

            $conta_invest = $crud->selectAll('conta_investimento', [['idContaInvest', '=', $old_invest]], [], [])[0];

            if ($old_tipo == '4' || $old_tipo == '2') {
                $saldo = $conta_invest['saldoAtual'] - $old_valor;
            } elseif ($old_tipo == '3' || $old_tipo == '1') {
                $saldo = $conta_invest['saldoAtual'] + abs($old_valor);
            }

            $crud->update(
                'conta_investimento', 
                ['saldoAtual' => $saldo], 
                ['idContaInvest' => $old_invest]
            );

            $objetivos = $crud->selectAll('obj', [['idContaInvest', '=', $old_invest]], [], []);
    
            foreach ($objetivos as $value) {
                $item = [
                    'saldoAtual' => ($saldo * ($value['percentObjContaInvest'] / 100))
                ];
                $item_where = ['idObj' => $value['idObj']];
                $crud->update('obj', $item, $item_where);
            }
        }

        if ($id_conta_invest != '') {
            switch ($_POST['idCategoria']) {
                case $this->APLICACAO:
                    $new_tipo = 4;
                    $valor_aplicado = ($_POST['valor'] * -1); 
    
                    $objetivos = $crud->selectAll('obj', [['idContaInvest', '=', $id_conta_invest]], [], []);
    
                    foreach ($objetivos as $value) {
                        $item = [
                            'saldoAtual' => $value['saldoAtual'] + ($valor_aplicado * ($value['percentObjContaInvest'] / 100))
                        ];
                        $item_where = ['idObj' => $value['idObj']];
                        $crud->update('obj', $item, $item_where);
                    }
    
                    break;
                case $this->RESGATE:
                    $new_tipo = 3;
                    $valor_aplicado = ($_POST['valor'] * -1); 
    
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
                    $new_tipo = '';
                    $valor_aplicado = 0;
            }

            $item = [
                'idContaInvest'   => $id_conta_invest,
                'valorRendimento' => $valor_aplicado,
                'dataRendimento'  => $_POST['dataMovimento'],
                'tipo'            => $new_tipo,
                'idMovimento'     => $id_movimento
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

    public function delete()
    {
        $crud = new CRUD();

        $id_mov = $_GET['idMovimento'];

        $rend = $crud->selectAll('rendimento', [['idMovimento', '=', $id_mov]], [], []);

        if (!empty($rend)) {
            return false;
        }

        $ret = $crud->delete($_GET);

        return $ret;
    }
}

class Rendimento
{
    public function update()
    {

    }

    public function insert()
    {

    }

    public function delete()
    {

    }
}

class ContaInvestimento
{
    public function update()
    {

    }

    public function insert()
    {

    }

    public function delete()
    {

    }
}

class Objetivos
{
    public function insert()
    {
        $crud = new CRUD();

        $id_conta_invest = $_POST['idContaInvest'];
        $percentual = $_POST['percentObjContaInvest'];

        $this->validations($crud, $id_conta_invest, $percentual);

        $id_obj = $crud->insert('obj', $_POST);
        $crud->atualizarSaldoObj($id_obj, $percentual, $id_conta_invest);
    }

    public function update()
    {
        $crud = new CRUD();

        $id = $_POST['idObj'];
        $conta_invest = $_POST['idContaInvest'];
        $percentual_old = $_POST['percentObjContaInvestOLD'];
        unset($_POST['idObj']);
        unset($_POST['idContaInvest']);
        unset($_POST['percentObjContaInvestOLD']);

        if ($_POST['percentObjContaInvest'] > $percentual_old) {
            $this->validations($crud, $conta_invest, ($_POST['percentObjContaInvest'] - $percentual_old));
        }

        if ($crud->update('obj', $_POST, ['idObj' => $id]) > 0) {
            echo 'Atualização realizada.';
        }
    }

    private function validations($crud, $id_conta_invest, $percentual)
    {
        $utilizado = $crud->consultarPercentualDisponivel($id_conta_invest, $percentual);

        if (($percentual + $utilizado) > 100) {
            echo '<div class="text-center"><span class="text-danger">Atenção!</span> A Conta Invest informada já está ' . $utilizado . '% comprometida.</div>';

            exit;
        }
    }
}

?>