<?php
    if (empty($_SESSION) || !isset($_SESSION))
        session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] !== true)
        header ("location: login.php");
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
        <nav class="navbar navbar-expand-lg navbar-light bg-light p-2">
            <a class="navbar-brand" href="index.php">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="cat_add_edit.php">Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mov_add_edit.php">Movimentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="indicadores.php">Indicadores</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>