<?php

namespace src\repository;

use PDO;
use src\model\User;
use src\utils\ModelMapper;

class UserRepository extends BaseRepository
{
    public function find($id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user['id'] == $id ? ModelMapper::mapUser($user) : null;
    }

    public function findAll(): array
    {

        $stmt = $this->db->query("SELECT * FROM users");
        $query = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $users = array();

        foreach ($query as $result) {
            $users[] = ModelMapper::mapUser($result);
        }

        return $users;
    }
}
