<?php 
    require 'scripts_sql.php';

    $crud = new CRUD();

    if (!empty($_POST) && isset($_POST['dataOrcamento'])) {
        if (isset($_POST['lote']) && $_POST['lote'] == 'T') {
            foreach ($_POST['idCategoria'] as $k => $id_cat) {
                $data['idCategoria'] = $id_cat;
                $data['valor'] = $_POST['valor'][$k];
                $data['dataOrcamento'] = $_POST['dataOrcamento'] . '-01';

                $crud->insert('orcamento', $data);
            }
        } else {
            $crud->insert('orcamento', $_POST);
        }
    }
        

    header('location: ../public/orcamento.php');

