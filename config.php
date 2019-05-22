<?php

try{
    $pdo = createPDO();
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $err){
    die("ERROR: Could not connect. " . $err->getMessage());
}
//creation of pdo
function createPDO(){
// Configuration for database connection
    $host       = "localhost:81";
    $username   = "root";
    $password   = "";
    $dbname     = "tictactoe";
    $dsn        = "mysql:host=$host;dbname=$dbname"; // will use later

    $pdo = new PDO($dsn, $username, $password);
    return $pdo;
}

?>