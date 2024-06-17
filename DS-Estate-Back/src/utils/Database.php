<?php

namespace src\utils;

use PDO;
use PDOException;

class Database
{
    private string $host = 'mysql-container:3306'; // Docker service name
    private string $db = 'booking_app';
    private string $user = 'appuser';
    private string $pass = 'password';
    private string $charset = 'utf8mb4';
    private PDO $pdo;
    private string $error;
    private static ?Database $instance = null;

    private function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public static function getInstance(): ?Database
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
