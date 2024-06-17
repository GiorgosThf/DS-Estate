<?php

namespace src\repository;

use PDO;
use PDOException;
use src\model\User;
use src\utils\ModelMapper;

class AuthRepository extends BaseRepository
{

    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(User $user): ?int
    {
        $sql =
            "INSERT INTO users (first_name, last_name, username, password, email) VALUES (:first_name, :last_name, :username, :password, :email)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':first_name', $user->getFirstName());
        $stmt->bindValue(':last_name', $user->getLastName());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':email', $user->getEmail());

        if ($stmt->execute()) {
            return (int)$this->db->lastInsertId();
        }

        return null;
    }
}
