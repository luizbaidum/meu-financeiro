<?php 
    require 'scripts_sql.php';

    if (!empty($_POST)) { 
        $crud = new CRUD();

        if (isset($_POST['cadastro']) && $_POST['cadastro'] == 'T') {
            unset($_POST['cadastro']);
            $crud->insert('mensais', $_POST);

            header('location: ../public/movimentos_mensais.php');
        }

        if (isset($_POST['registro']) && $_POST['registro'] == 'T') {
            $item = array();

            foreach ($_POST['idMovMensal'] as $id) {
                $arr_cat = explode(' - sinal: ', $_POST['idCategoria'][$id]);
                $sinal = $arr_cat[1];

                $item['nomeMovimento'] = $_POST['nomeMovimento'][$id];
                $item['dataMovimento'] = $_POST['dataMovimento'][$id];
                $item['proprietario'] = $_POST['proprietario'][$id];
                $item['idCategoria'] = $arr_cat[0];
                $item['valor'] = $sinal . $_POST['valor'][$id];

                $crud->insert('movimento', $item);

                header('location:  ../public/movimentos_mensais_index.php');
            }
        }
    }