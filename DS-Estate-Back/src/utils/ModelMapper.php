<?php

namespace src\utils;

use src\model\DateAvailability;
use src\model\Property;
use src\model\Reservation;
use src\model\User;

class ModelMapper
{


    public static function mapProperty(array $data): ?Property
    {
        $id = $data['id'] ?? null;
        $title = $data['title'];
        $imageUrl = $data['photo_url'];
        $location = $data['area'];
        $rooms = $data['number_of_rooms'];
        $price = $data['price_per_night'];

        return new Property($id, $title, $imageUrl, $location, $rooms, $price);
    }

    public static function mapUser(array $data): User
    {
        return new User(
            $data['id'],
            $data['first_name'],
            $data['last_name'],
            $data['username'],
            $data['password'],
            $data['email']
        );
    }

    public static function mapReservation(array $data): ?Reservation
    {

        $listingId = $data['listing_id'];
        $userId = $data['user_id'];
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        $totalPrice = floatval($data['total_price']);

        return new Reservation($listingId, $userId, $startDate, $endDate, $totalPrice);
    }

    public static function mapAvailability(array $data): ?DateAvailability
    {

        $listingId = $data['listing_id'] ?? null;
        $startDate = $data['start_date'] ?? null;
        $endDate = $data['end_date'] ?? null;

        return new DateAvailability($listingId, $startDate, $endDate);
    }
}
