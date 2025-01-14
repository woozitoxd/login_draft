<?php

function conectar_db()
{
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $port = 3306;
    $db = "registrousuarios";  //Mi base de datos

    try {
        $newConnection = new \PDO("mysql:host=$servername;port=$port;dbname=" . $db . ";charset=utf8", $username, $password);
        // set the PDO error mode to exception
        $newConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully";
    } catch (Exception $e) {
        echo "Connection failed: " . $e->getMessage();
        die(); // exit;
    }
    return $newConnection;
}

if (!isset($GLOBALS['conn'])) {
    $GLOBALS['conn'] = conectar_db();  //Puedo conectarme a la base de datos usando un unico archivo
}
