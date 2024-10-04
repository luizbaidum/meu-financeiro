
<?php
    require 'header.php';

    $resultado_ext = 0;
    $months = array('Todos', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'); 

    $crud = new CRUD();
    $extrato = $crud->consultarExtrato($_POST);
    $lista_invest = $crud->selectAll('conta_investimento', [], [], []);
    $lista_acao = $crud->selectAll('rendimento', [], ['rendimentos', 'tipo'], []);

    $invest_selecionado = '';
    $mes_selecionado = '';
    $acao_selecionada = '';
    if (!empty($_POST)) {
        $mes_selecionado = $_POST['extratoMes'];
        $invest_selecionado = $_POST['extratoInvest'];
        $acao_selecionada = $_POST['acaoInvest'];
    }
?>

    <main class="container">
        <div class="card mt-2">
            <div class="card-header">Filtro</div>
            <div class="card-body p-1">
                <form method="POST" action="contas_investimentos_extrato.php">
                    <div class="form-group m-2">
                        <div class="row">
                            <div class="col-3">
                                <label for="idExtratoMes">Mês</label>
                                <select class="form-select" id="idExtratoMes" name="extratoMes">
                                    <option value="">Trimestre</option>
                                    <?php foreach ($months as $v): ?>
                                        <option value="<?= $v; ?>"<?= ($v == $mes_selecionado ? ' selected ' : ''); ?>><?= $v; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="idExtratoInvest">Invest</label>
                                <select class="form-select" id="idExtratoInvest" name="extratoInvest">
                                    <option value="">Todos</option>
                                    <?php foreach ($lista_invest as $v): ?>
                                        <option value="<?= $v['idContaInvest']; ?>"<?= ($v['idContaInvest'] == $invest_selecionado ? ' selected ' : ''); ?>><?= $v['nomeBanco'] . ' - ' . $v['tituloInvest']; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="idAcaoInvest">Ação</label>
                                <select class="form-select" id="idAcaoInvest" name="acaoInvest">
                                    <option value="">Todos</option>
                                    <?php 
                                        foreach ($lista_acao as $v): 
                                            $acao = match ($v['tipo']) {
                                                '1' => 'Prejuízo',
                                                '2' => 'Lucro',
                                                '3' => 'Resgate',
                                                '4' => 'Aplicação'
                                            };
                                    ?>
                                        <option value="<?= $v['tipo']; ?>"<?= ($v['tipo'] == $acao_selecionada ? ' selected ' : ''); ?>><?= $acao; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-3 pt-3">
                                <button class="btn btn-primary mt-1" type="submit">Aplicar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-1">
            <div class="card-header p-2">
                Extrato
            </div>
            <div class="card-body p-1">
                <table class="table">
                    <theader>
                        <tr>
                            <th>Conta</th>
                            <th>Data</th>
                            <th>Ação</th>
                            <th>Valor</th>
                        </tr>
                    </theader>
                    <tbody>
                        <?php 
                            foreach ($extrato as $value): 
                                $acao = match ($value['tipo']) {
                                    '1' => 'Prejuízo',
                                    '2' => 'Lucro',
                                    '3' => 'Resgate',
                                    '4' => 'Aplicação'
                                };

                                $resultado_ext += $value['valorRendimento'];
                        ?>
                            <tr>
                                <td><?= $value['conta']; ?></td>
                                <td><?= $value['dataRendimento']; ?></td>
                                <td><?= $acao; ?></td>
                                <td>$ <?= $value['valorRendimento']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <td colspan="3" style="text-align: right">Resultado</td>
                            <td>$ <?= $resultado_ext; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </main>

<?php require 'bottom.php'; ?>