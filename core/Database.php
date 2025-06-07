<?php

class Database {
    private $pdo;

    public function __construct() {
        $config = require __DIR__ . '/../config/db_config.php';

        try {
            $this->pdo = new PDO(
                "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_db']}",
                $config['db_user'],
                $config['db_password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("DB Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}