<?php
$dbtype = "mysql";
$Server = "103.239.252.183";
//$Server = "localhost";
$UserID = "root";
$Password = "nopass";
$Database = "rin_career_ready_academy";

$dsn = 'mysql:host=' . $Server . ';dbname=' . $Database . '';

global $pdo;

try {
    $pdo = new PDO($dsn, $UserID, $Password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "connection successful";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


