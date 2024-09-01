<?php 
    require_once 'sql/scripts_sql.php';

    $logado = false;

    if (!empty($_POST)) {
        $crud = new CRUD();
        $logado = $crud->fazerLogin($_POST);

        if ($logado) {
            session_start();
            $_SESSION["logado"] = true;

            unset($_POST["login"]);
            unset($_POST["senha"]);

            header ("location: index.php");
        }
    } else {
        session_start();
        if (isset($_SESSION["logado"]) && $_SESSION["logado"] == true) 
            session_destroy();
    }
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
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <form class="p-3 mt-3" method="post" action="login.php">
                        <div class="form-field d-flex align-items-center">
                            <span class="far fa-user"></span>
                            <input type="text" name="login" id="login" placeholder="Login">
                        </div>
                        <div class="form-field d-flex align-items-center">
                            <span class="fas fa-key"></span>
                            <input type="password" name="senha" id="idSenha" placeholder="Senha">
                        </div>
                        <button type="submit" class="btn btn-secondary mt-3">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>