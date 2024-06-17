<?php

namespace src\repository;

use PDO;
use src\utils\ModelMapper;

class PropertyRepository extends BaseRepository
{

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM listings WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM listings");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function create(array $data): bool
    {
        $property = ModelMapper::mapProperty($data);

        $sql = "INSERT INTO listings (title, photo_url, area, number_of_rooms, price_per_night) 
            VALUES (?, ?, ?, ?, ?)";

        // Bind parameters
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $property->getTitle());
        $stmt->bindValue(2, $property->getImageUrl());
        $stmt->bindValue(3, $property->getLocation());
        $stmt->bindValue(4, $property->getRooms());
        $stmt->bindValue(5, $property->getPrice());

        return $stmt->execute();

    }

    public function findAllByUser($id): bool|array
    {
        $stmt = $this->db->prepare("SELECT * FROM listings WHERE owner_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
