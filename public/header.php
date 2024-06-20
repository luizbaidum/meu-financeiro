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

    <style>
        body {
            margin: 0.2%;
            background-color: #33A5FF;
        }
    </style>
</head>
<body>
    <header class="container mb-2">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownCadastros" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Cadastros
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownCadastros">
                                <li><a class="dropdown-item" href="cad_categorias.php">Categorias</a></li>
                                <li><a class="dropdown-item" href="cad_movimentos.php">Movimentos</a></li>
                                <li><a class="dropdown-item" href="cad_contas_investimentos.php">Contas Invest</a>
                                <li><a class="dropdown-item" href="cad_movimentos_mensais.php">Movimentos Mensais</a>
                                <li><a class="dropdown-item" href="cad_objetivos.php">Objetivos</a>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownConsultas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Consultas
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownConsultas">
                                <li><a class="dropdown-item" href="indicadores.php">Indicadores</a></li>
                                <li><a class="dropdown-item" href="orcamento.php">Orçamento</a></li>
                                <li><a class="dropdown-item" href="contas_investimentos.php">Contas Invest</a>
                                <li><a class="dropdown-item" href="movimentos_mensais.php">Movimentos Mensais</a>
                            </ul>
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