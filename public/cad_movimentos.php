
<?php
    require_once ("header.php");

    const APLICACAO = '12';
    const RESGATE = '10';

    $crud = new CRUD();
    
    $options_list = json_encode($crud->selectAll('obj', [], [], []));
    echo '<script>var options_obj = ' . $options_list . '</script>';

    if (!empty($_POST)) {

        $arr_cat = explode(' - sinal: ' , $_POST['idCategoria']);
        $_POST['idCategoria'] = $arr_cat[0];
        $sinal = $arr_cat[1];

        if (($_POST['idCategoria'] == APLICACAO || $_POST['idCategoria'] == RESGATE) && empty($_POST['idContaInvest'])) {
            echo '<div class="text-danger text-center">É obrigatório escolher uma conta investimento.</div>';
            exit;

            if ($_POST['idCategoria'] == RESGATE && empty($_POST['idObjetivo'])) {
                echo '<div class="text-danger text-center">É obrigatório escolher um objetivo.</div>';
                exit;
            }

            if ($_POST['idCategoria'] == RESGATE) {
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
                case APLICACAO:
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
                case RESGATE:
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
?>

    <main class="container">
        <div class="card p-1">
            <form action="cad_movimentos.php" method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label for="idNomeMovimento">Nome Movimento</label>
                            <input type="text" class="form-control" id="idNomeMovimento" name="nomeMovimento" required>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="idDataMovimento">Data Movimento (m/d/a)</label>
                            <input type="date" class="form-control" id="idDataMovimento" name="dataMovimento" required>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="idCategoria">Categoria</label>
                            <select class="form-select select-categoria" id="idCategoria" name="idCategoria" required>
                                <option value="">Selecione</option>
                                <?php 
                                    $categorias = $crud->selectAll("categoria", [], [], ["tipo" => "ASC", "categoria" => "ASC"]);
                                    foreach ($categorias as $cat):
                                ?>
                                    <option value="<?= $cat["idCategoria"] . " - sinal: " . $cat["sinal"]; ?>"><?= $cat["categoria"] . " - " . $cat["tipo"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="tipo">Valor (EUA e apenas inteiro)</label>
                                <input type="number" class="form-control" id="idValor" name="valor" required>
                            </div>
                            <div class="col-3">
                                <label for="idContaInvest">Conta Invest (se houver)</label>
                                <select class="form-select" id="idContaInvest" name="idContaInvest">
                                    <option value="">Selecione</option>
                                    <?php 
                                        $invests = $crud->selectAll("conta_investimento", [], [], ["nomeBanco" => "ASC"]);
                                        foreach ($invests as $value):
                                    ?>
                                        <option value="<?= $value["idContaInvest"]; ?>"><?= $value["nomeBanco"] . " - " . $value["tituloInvest"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div id="content-obj" class="col-3"></div>
                           <!-- <div class="col-6 mt-4">
                                <label for="tipo">No Cartão?</label>
                                <input type="checkbox" id="idCartao" name="cartao" value="1">
                            </div> -->
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </form>
        </div>
    </main>

<?php include_once ("bottom.php"); ?>