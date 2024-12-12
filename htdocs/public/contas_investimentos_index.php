
<?php
    require 'header.php';

    $crud = new CRUD();
    $contas = $crud->selectAll('conta_investimento', [], [], []);

    $total = 0;
    $resultado_ext = 0;
    $total_uepa = 0;
    $total_lb = 0;
?>

    <main class="container">
        <div class="card mt-2">
            <div class="card-header">
                <button class="btn btn-light btn-sm nav-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCadRendimento" aria-expanded="false" aria-controls="collapseCadRendimento">
                    Cadastrar Rendimento
                </button>
            </div>
            <div class="collapse" id="collapseCadRendimento">
                <div class="card-body p-1">
                    <form action="../sql/cad_rendimento.php" method="post">
                        <input type="hidden" name="cadRendimento" value="1">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="idDataRendimento">Data</label>
                                    <input type="date" class="form-control" id="idDataRendimento" name="dataRendimento" required>
                                </div>
                                <div class="col-6">
                                    <label for="idValorRendimento">Valor (decimal com ponto)</label>
                                    <input type="number" class="form-control" id="idValorRendimento" name="valorRendimento" step=".01" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="idContaInvest">Conta Invest</label>
                                        <select class="form-select" id="idContaInvest" name="idContaInvest">
                                            <option value="">Selecione</option>
                                        <?php 
                                            $invests = $crud->selectAll('conta_investimento', [], [], ["nomeBanco" => "ASC"]);
                                            foreach ($invests as $value):
                                        ?>
                                            <option value="<?= $value['idContaInvest']; ?>"><?= $value['nomeBanco'] . ' - ' . $value['tituloInvest']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="idTipo">Tipo</label>
                                        <select class="form-select" id="idTipo" name="tipo">
                                            <option value="">Selecione</option>
                                            <option value="1">1 - Preju</option>
                                            <option value="2">2 - Lucro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-1">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card mt-1">
            <div class="card-body">
                <table class="table">
                    <theader>
                        <tr>
                            <th>Banco</th>
                            <th>Título</th>
                            <th>Objetivos</th>
                            <th>Saldo atual</th>
                            <th class="bg-secondary">Saldo anterior</th>
                            <th class="bg-secondary">Data anterior</th>
                        </tr>
                    </theader>
                    <tbody>
                        <?php 
                            foreach ($contas as $value): 
                                $total += $value['saldoAtual'];

                                if ($value['proprietario'] == '1') {
                                    $total_lb += $value['saldoAtual'];
                                } elseif ($value['proprietario'] == '2') {
                                    $total_uepa += $value['saldoAtual'];
                                }
                        ?>
                            <tr>
                                <td><?= $value['nomeBanco']; ?></td>
                                <td><?= $value['tituloInvest']; ?></td>
                                <td><button class="consultar-objetivo" 
                                            data-url-action="../sql/consultas.php"
                                            data-id="<?= $value['idContaInvest']; ?>"
                                            data-action="consultar-objetivos"
                                            data-field-id="idContaInvest"
                                            data-table="obj"
                                            data-method="GET"
                                            data-callback="dispararAlert"
                                    >&#8505;</button>
                                    <a href="edit_objetivos.php?idContaInvest=<?= $value['idContaInvest']; ?>">
                                        <button>&#9998;</button>
                                    </a>
                                </td>
                                <td>R$ <?= NumbersHelper::formatUStoBR($value['saldoAtual']); ?></td>
                                <td>R$ <?= NumbersHelper::formatUStoBR($value['saldoAnterior']); ?></td>
                                <td><?= DateHelper::convertUStoBR($value['dataAnterior']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <td colspan="3" style="text-align: right">Total</td>
                            <td>R$ <?= NumbersHelper::formatUStoBR($total); ?>
                            <td>
                                <small>Luiz: <?= NumbersHelper::formatUStoBR($total_lb); ?> <br> Uepa: <?= NumbersHelper::formatUStoBR($total_uepa); ?></small>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <?php $objs = $crud->selectAll('obj', [], [], ['saldoAtual' => 'DESC']);
                $arr = [];
                $total = 0;
                foreach ($objs as $value) {
                    if (isset($arr[$value['nomeObj']])) {
                        $arr[$value['nomeObj']]['saldo'] += $value['saldoAtual'];
                        $arr[$value['nomeObj']]['meta'] += $value['vlrObj'];
                    } else {
                        $arr[$value['nomeObj']]['saldo'] = $value['saldoAtual'];
                        $arr[$value['nomeObj']]['meta'] = $value['vlrObj'];
                    }

                    $total += $value['saldoAtual'];
                } 
        ?>
        <div class="card mt-1">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <table class="table">
                            <theader>
                                <tr>
                                    <th>Objetivo</th>
                                    <th>Valor</th>
                                    <th>Meta</th>
                                    <th>% ating.</th>
                                </tr>
                            </theader>
                            <tbody>
                            <?php 
                                foreach ($arr as $k => $val): ?>
                                <tr>
                                    <td><?= $k; ?></td>
                                    <td><?= NumbersHelper::formatUStoBR($val['saldo']); ?></td>
                                    <td><?= NumbersHelper::formatUStoBR($val['meta']); ?></td>
                                    <td><?= NumbersHelper::formatUStoBR(number_format(($val['saldo'] / $val['meta']) * 100, 2)); ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-dark">
                                    <td>Total:</td>
                                    <td><?= NumbersHelper::formatUStoBR($total); ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>   
                    </div>
                </div>       
            </div>
        </div>
    </main>

<?php require 'bottom.php'; ?>