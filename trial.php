<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'renier_db';
$charset = 'utf8mb4';

try {
    $connection = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    
    echo json_encode(["success" => false, "message" => "Connection failed: " . $e->getMessage()]);
    exit;
}