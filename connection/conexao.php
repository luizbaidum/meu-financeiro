<?php
    function gerarConexao()
    {
        global $con;

        $host = "localhost";
        $db = "meu_financeiro";
        $user = "root";
        $pw = "";

        $con = new PDO("mysql:host=$host; dbname=$db", $user, $pw, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            )
        );

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $con;
    }
?>