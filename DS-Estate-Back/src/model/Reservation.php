<?php

namespace src\model;

use DateTime;
use JsonSerializable;
use ReturnTypeWillChange;


class Reservation implements JsonSerializable
{
    private string $id;
    private string $listingId;
    private string $userId;
    private DateTime $startDate;
    private DateTime $endDate;
    private float $totalPrice;

    /**
     * @param string $listingId
     * @param string $userId
     * @param string $startDate
     * @param string $endDate
     */
    public function __construct(string $listingId, string $userId, string $startDate, string $endDate, float $totalPrice)
    {
        $this->listingId = $listingId;
        $this->userId = $userId;
        $this->startDate = $this->toDateTime($startDate);
        $this->endDate = $this->toDateTime($endDate);
        $this->totalPrice = $totalPrice;
    }


    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getListingId(): string
    {
        return $this->listingId;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function toDateTime(string $date): ?DateTime
    {
        try {
            return new DateTime($date);
        } catch (\Exception $e) {
            return null;
        }
    }


    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


}
