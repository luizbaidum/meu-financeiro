<?php 

if (!isset($_GET['idContaInvest']) || (isset($_GET['idContaInvest']) && $_GET['idContaInvest'] == '')) {
    echo '<div class="text-danger text-center">Acesso não permitido</div>';
    exit;
}

require_once 'header.php';

$crud = new CRUD();
$id_conta_invest = $_GET['idContaInvest'];

$objetivos = $crud->selectAll('obj', ['idContaInvest', '=', $id_conta_invest], [], []);

?>

<main class="container">
    <div class="card mt-2">
        <div class="card-body">
            
        </div>
    </div>
</main>