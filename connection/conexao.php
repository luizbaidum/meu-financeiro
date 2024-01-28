<?php

$host = "localhost";
$db = "meu-financeiro";
$user = "root";
$pw = "";

global $con;

$con = new \PDO("mysql:host=$host; dbname=$db", $user, $pw);
$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

?>