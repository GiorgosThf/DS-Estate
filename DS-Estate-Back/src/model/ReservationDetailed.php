<?php

namespace src\model;

use JsonSerializable;
use ReturnTypeWillChange;

class ReservationDetailed implements JsonSerializable
{
    private Reservation $reservation;
    private Property $property;
    private User $user;


    public function __construct(Reservation $reservation, User $user, Property $property)
    {
        $this->reservation = $reservation;
        $this->user = $user;
        $this->property = $property;
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}
