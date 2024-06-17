<?php

namespace src\model;

use JsonSerializable;
use ReturnTypeWillChange;

class Property implements JsonSerializable
{

    private ?string $id;
    private string $title;
    private string $imageUrl;
    private string $location;
    private int $rooms;
    private float $price;


    public function __construct($id, $title, $imageUrl, $location, $rooms, $price)
    {
        $this->id = $id;
        $this->title = $title;
        $this->imageUrl = $imageUrl;
        $this->location = $location;
        $this->rooms = $rooms;
        $this->price = $price;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getRooms(): int
    {
        return $this->rooms;
    }

    public function getId(): string
    {
        return $this->id;
    }


    public function setId(string $propertyId): void
    {
        $this->id = $propertyId;
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
