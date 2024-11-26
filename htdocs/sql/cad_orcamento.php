<?php 
    require 'scripts_sql.php';

    $crud = new CRUD();

    if (!empty($_POST) && isset($_POST['dataOrcamento'])) {
        if (isset($_POST['lote']) && $_POST['lote'] == 'T') {
            foreach ($_POST['idCategoria'] as $k => $categoria) {
                $arr_cat = explode(' - sinal: ' , $categoria);
                $data['idCategoria'] = $arr_cat[0];
                $sinal = $arr_cat[1];

                $data['valor'] = $_POST['valor'][$k];
                if ($sinal == '-') {
                    $data['valor'] = $data['valor'] * -1;
                }

                $data['dataOrcamento'] = $_POST['dataOrcamento'] . '-01';

                $crud->insert('orcamento', $data);
            }
        } else {
            $arr_cat = explode(' - sinal: ' , $_POST['idCategoria']);
            $_POST['idCategoria'] = $arr_cat[0];
            $sinal = $arr_cat[1];

            if ($sinal == '-' && !strpos($_POST['valor'], '-'))
                $_POST['valor'] = $sinal . $_POST['valor'];

            $crud->insert('orcamento', $_POST);
        }
    }

    header('location: ../public/orcamento.php');