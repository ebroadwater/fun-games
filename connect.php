<?php 
$config = require 'db_config.php'; 

try {
    $pdo = new PDO(
        "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_db']}",
        $config['db_user'],
        $config['db_password']
    );
    echo "Connected!";
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
