<?php

namespace src\repository;

use PDO;
use src\model\Reservation;
use src\utils\CustomLogger;

class ReservationRepository extends BaseRepository
{
    public CustomLogger $customLogger;

    public function create(Reservation $reservation): ?Reservation
    {
        if ($this->isAvailable($reservation->getListingId(), $reservation->getStartDate()->format('Y-m-d'), $reservation->getEndDate()->format('Y-m-d')) == 0) {
            return null;
        }
        // Prepare the SQL statement
        $sql = "INSERT INTO reservations (listing_id, user_id, start_date, end_date,total_price)
            VALUES (?, ?, ?, ?, ?)";

        // Bind parameters
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $reservation->getListingId());
        $stmt->bindValue(2, $reservation->getUserId());
        $stmt->bindValue(3, $reservation->getStartDate()->format('Y-m-d'), PDO::PARAM_STR);
        $stmt->bindValue(4, $reservation->getEndDate()->format('Y-m-d'), PDO::PARAM_STR);
        $stmt->bindValue(5, $reservation->getTotalPrice());

        // Execute the statement
        $stmt->execute();

        // Optionally, retrieve the ID of the newly inserted row
        $reservationId = $this->db->lastInsertId();

        // Update the property object with the assigned ID
        $reservation->setId($reservationId);

        return $reservation;
    }

    public function isAvailable(int $listingId, string $startDate, string $endDate): bool
    {
        $this->customLogger = new CustomLogger();


        $sql = "CALL CheckAvailability(:listing, :start_date, :end_date, @is_available)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':listing', $listingId, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->execute();

        $result = $this->db->query("SELECT @is_available AS is_available")->fetch(PDO::FETCH_ASSOC);
        $this->customLogger->info($result['is_available']);
        return $result['is_available'];
    }

    public function findByUser(string $userId): bool|array
    {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE user_id = :id");
        $stmt->execute(['id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
