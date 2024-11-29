<?php
    header('Content-type: text/html; charset=utf-8');    
    setlocale(LC_ALL, NULL);
    setlocale(LC_ALL, 'pt_BR.utf-8');

    require_once '../sql/scripts_sql.php';

    $pagina_atual = $_SERVER['REQUEST_URI'];
    $lista_paginas = [
        'categorias.php' => 'Categorias',
    ];

    session_start();

    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        if (!isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] != '/erros_do_bog.php') {
            header ('location: login.php');
        }
    }

    $lembretes = (new CRUD())->selectAll('lembrete', [], [], []);
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
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownCadastros" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Cadastros
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownCadastros">
                                <li>
                                    <a class="dropdown-item" href="categorias.php">Categorias</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="movimentos.php">Movimentos</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="contas_investimentos.php">Contas Invest</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="movimentos_mensais.php">Movimentos Mensais</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="objetivos.php">Objetivos</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="orcamento.php">Orçamento</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownConsultas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Consultas
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownConsultas">
                                <li><a class="dropdown-item" href="indicadores.php">Indicadores</a></li>
                                <li><a class="dropdown-item" href="contas_investimentos_index.php">Lista Contas Invest</a>
                                <li><a class="dropdown-item" href="contas_investimentos_extrato.php">Extrato Contas Invest</a>
                                <li><a class="dropdown-item" href="movimentos_mensais_index.php">Movimentos Mensais</a>
                                <li>
                                    <a class="dropdown-item" href="orcamento_index.php">Orçamento</a>
                                </li>
                            </ul>
                        </li>
                        <?php if ($_SERVER['REQUEST_URI'] != '/erros_do_bog.php'): ?>
                            <li class="nav-item">
                                <button class="btn btn-light btn-sm nav-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLembretes" aria-expanded="false" aria-controls="collapseLembretes">Lembretes</button>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="login.php">
                                <button type="button" class="btn btn-danger">Sair</button>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="erros_do_bog.php">
                                <button type="button" class="btn btn-light">Erros do bog</button>
                            </a>
                        </li>      
                    </ul>

                    <form class="form-ajax d-flex" data-url-action="index.php" data-method="POST" role="search">
                        <input type="text" class="form-control me-2" id="idPesquisa" name="pesquisa" value="<?= $pesquisa ?? ''; ?>">                        
                        <button type="submit" class="btn btn-outline-primary">Pesquisar</button>
                    </form>
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
                            <div class="row">
                                <div class="col-11">
                                    <?= $value["lembrete"]; ?> 
                                </div>
                                <div class="col-1 acao-deletar" 
                                    style="width: 40px; cursor: pointer" 
                                    data-chave="<?= $value['idLembrete']; ?>" 
                                    data-table="lembrete"
                                    data-campo="idLembrete"
                                >
                                    <h5>X</h5>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>