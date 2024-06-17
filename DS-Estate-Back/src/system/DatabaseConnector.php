<?php

namespace src\system;

use PDO;
use PDOException;

class DatabaseConnector
{
    private ?PDO $dbConnection = null;
    private static ?DatabaseConnector $instance = null;

    public function __construct()
    {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $db = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');
        $charset = getenv('DB_CHARSET');

        $dsn = "mysql:host=$host:$port;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->dbConnection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public static function getInstance(): ?DatabaseConnector
    {
        if (!self::$instance) {
            self::$instance = new DatabaseConnector();
        }
        return self::$instance;
    }

    public function getConnection(): ?PDO
    {
        return $this->dbConnection;
    }
}
