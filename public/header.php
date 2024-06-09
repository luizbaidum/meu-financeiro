<?php
    header('Content-type: text/html; charset=utf-8');    
    setlocale(LC_ALL, NULL);
    setlocale(LC_ALL, 'pt_BR.utf-8');

    require_once "scripts_sql.php";

    session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] !== true)
        header ("location: login.php");

    $lembretes = (new CRUD())->selectAll("lembrete", [], [], []);
?>

<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>Financas</title>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.css.map">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <style>
        body {
            margin: 0.2%;
            background-color: #33A5FF;
        }
    </style>
</head>
<body>
    <header class="container mb-2">
        <nav class="navbar navbar-expand-lg navbar-light bg-light p-2">
            <a class="navbar-brand" href="index.php">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Cadastros
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categorias.php">Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="movimentos.php">Movimentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="indicadores.php">Indicadores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orcamento.php">Orçamento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contas_investimentos.php">Contas Invest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="movimentos_mensais.php">Movimentos Mensais</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-light btn-sm nav-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLembretes" aria-expanded="false" aria-controls="collapseLembretes">Lembretes</button>
                    </li>
                    <li class="nav-item">
                        <a href="login.php">
                            <button type="button" class="btn btn-danger">Sair</button>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="m-2">
            <div class="collapse" id="collapseLembretes">
                <div class="card card-body">
                    <div>
                        <a class="btn btn-primary btn-sm" href="lembretes.php">Cadastrar</a>
                    </div>
                    <div>
                        <?php foreach ($lembretes as $value): ?>
                            <hr>
                            <?= $value["lembrete"]; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>