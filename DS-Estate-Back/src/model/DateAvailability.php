<?php

namespace src\model;

use JsonSerializable;
use ReturnTypeWillChange;

class DateAvailability implements JsonSerializable
{
    private int $listingId;
    private string $startDate;
    private string $endDate;


    public function __construct(int $listingId, string $startDate, string $endDate)
    {
        $this->listingId = $listingId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getListingId(): string
    {
        return $this->listingId;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }


    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


}
