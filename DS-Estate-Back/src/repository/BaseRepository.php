<?php

namespace src\repository;

use PDO;
use src\utils\Database;

abstract class BaseRepository
{
    public PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

}
