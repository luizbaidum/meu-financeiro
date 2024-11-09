<?php 
    require_once 'header.php'; 

    $crud = new CRUD();

    $pesquisa = '';
    $saldos_anteriores = array();
    $resultado = 0;
    $acumulado = 0;
    $result_lb = 0;
    $result_uepa = 0;

    if (!empty($_POST['pesquisa']) && trim($_POST['pesquisa'], ' ') != '') {
        $pesquisa = trim($_POST['pesquisa'], ' ');
    }

    if (isset($_POST['mesFiltro']) && !empty($_POST['mesFiltro'])) {
        $movimentos = $crud->indexTable($pesquisa, $_POST['mesFiltro']);
    } else {
        $movimentos = $crud->indexTable($pesquisa);
        $saldos_anteriores = $crud->getSaldoPassado();
    }

    if ($pesquisa != '') {
        $saldos_anteriores = array();
    }

    $options_list = json_encode($crud->selectAll('categoria', [], [], []));
    echo '<script>var options_obj = ' . $options_list . '</script>';
    echo '<script>var proprietario_obj = [
            {
                "idCategoria": "1", "categoria": "Luiz"
            },
            {
                "idCategoria": "2", "categoria": "Uepa"
            }
                ]
        </script>';
?>
     
    <main class="container">
        <form action="index.php" data-method="post" id="idFormMesFiltro">
            <?php require_once "select_month.php"; ?>
        </form>

        <table class="table">
            <theader>
                <tr>
                    <th>Data</th>
                    <th>Movimento</th>
                    <th>Categoria</th>
                    <th>Valor</th>
                    <th>Prop.</th>
                    <th style="width: 90px">Ações</th>
                </tr>
            </theader>
            <tbody>
                <?php 
                    foreach ($movimentos as $mov): 
                        $resultado += $mov['valor'];

                        switch ($mov['proprietario']) {
                                case '1':
                                    $prop = 'Luiz';
                                    $result_lb += $mov['valor'];
                                    break;
                                case '2':
                                    $prop = 'Uepa';
                                    $result_uepa += $mov['valor'];
                                    break;
                                default:
                                    $prop = '';
                        }
                ?>
                    <tr>
                        <td class="dataMovimento" data-element-type="input"><?= $mov['dataMovimento']; ?></td>
                        <td class="nomeMovimento" data-element-type="input"><?= $mov['nomeMovimento']; ?></td>
                        <td class="idCategoria <?= $mov['idCategoria']; ?>" data-element-type="select" data-element-opts="options_obj"><?= $mov['categoria']; ?></td>
                        <td class="valor" data-element-type="input">$ <?= $mov['valor']; ?></td>
                        <td class="proprietario <?= $mov['proprietario']; ?>" data-element-type="select" data-element-opts="proprietario_obj">
                            <?= $prop; ?>
                        </td>
                        <td>
                            <a href="movimentos.php?idMovimento=<?= $mov['idMovimento']; ?>" class="link-offset-2 link-underline link-underline-opacity-0">
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Editar" data-table="movimento">
                                    &#63;
                                </button>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="<?= $mov['idMovimento']; ?>" data-table="movimento" title="Excluir">
                                &#10005;
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <td colspan="3" style="text-align: right">Resultado (Rec. - Des. - Apli.)</td>
                    <td>$ <?= $resultado; ?></td>
                    <td>
                        <small>Luiz: <?= $result_lb; ?> <br> Uepa: <?= $result_uepa; ?></small>
                    </td>
                    <td></td>
                </tr>
                <?php 
                    if (!empty($saldos_anteriores)): 
                        $acumulado = $resultado;
                ?>
                    <?php 
                        foreach ($saldos_anteriores as $value): 
                            $acumulado += + $value['valor'];
                    ?>
                        <tr class="table-dark">
                            <td colspan="3" style="text-align: right">Resultado mês <?= $value['MES']; ?>:</td>
                            <td>$ <?= $value['valor']; ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-dark">
                        <td colspan="3" style="text-align: right">Acumulado</td>
                        <td>$ <?= $acumulado; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>
    </main>

<?php include_once 'bottom.php'; ?>