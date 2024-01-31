<?php
    function gerarConexao()
    {
        global $con;

        $host = "localhost";
        $db = "meu_financeiro";
        $user = "root";
        $pw = "";

        $con = new \PDO("mysql:host=$host; dbname=$db", $user, $pw);
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
        return $con;
    }
?>