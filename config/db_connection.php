<?php

// change the database configuration
$servername = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'stock_management';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=stock_management", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed';
}
