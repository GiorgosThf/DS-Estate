<?php

namespace src\service;

use DateTime;
use PDOException;
use src\model\DateAvailability;
use src\model\Reservation;
use src\model\ReservationDetailed;
use src\repository\PropertyRepository;
use src\repository\ReservationRepository;
use src\repository\UserRepository;
use src\utils\CustomLogger;
use src\utils\ModelMapper;

class ReservationService
{
    private ReservationRepository $reservationRepository;
    private CustomLogger $customLogger;
    private UserRepository $userRepository;
    private PropertyRepository $propertyRepository;

    public function __construct()
    {
        $this->reservationRepository = new ReservationRepository();
        $this->userRepository = new UserRepository();
        $this->propertyRepository = new PropertyRepository();
        $this->customLogger = new CustomLogger();
    }


    public function find($id): Reservation
    {
        return $this->reservationRepository->find($id);
    }

    public function isAvailable(array $reqData): array
    {
        $this->customLogger->info(json_encode($reqData));

        $availability = ModelMapper::mapAvailability($reqData);
        try {
            $isAvail = $this->reservationRepository->isAvailable($availability->getListingId(),
                $availability->getStartDate(), $availability->getEndDate());

            $this->customLogger->info(json_encode($isAvail));
            header('HTTP/1.1 200 OK');
            return $isAvail ? [
                'success' => true,
                'message' => 'Dates are available',
                'isAvail' => true,
                'totalCost' => $this->calculateCost($availability)
            ] : [
                'success' => false,
                'message' => 'Dates are not available',
                'isAvail' => false,
            ];
        } catch (PDOException $e) {
            header('HTTP/1.1 404 Not Found');
            return [
                'success' => false,
                'message' => $e->getMessage()];
        }
    }

    public function create(array $resData): array
    {
        $this->validateData($resData);

        $reservation = ModelMapper::mapReservation($resData);

        $res = $this->reservationRepository->create($reservation);

        if ($res != null) {

            $user = $this->userRepository->find($res->getUserId());
            $list = ModelMapper::mapProperty($this->propertyRepository->find($res->getListingId()));
            $detailed = new ReservationDetailed($res, $user, $list);

            header('HTTP/1.1 200 OK');
            return [
                'success' => true,
                'message' => 'Reservation created successfuly',
                'reservation' => $detailed,
            ];
        }

        header('HTTP/1.1 404 Not Found');
        return [
            'success' => false,
            'message' => 'Checkin and checkout dates are not available',
        ];
    }

    private function validateData(array $resData): void
    {
        $requiredFields = ['listing_id', 'user_id', 'start_date', 'end_date', 'total_price'];
        foreach ($requiredFields as $field) {
            if (!isset($resData[$field])) {
                echo "Missing required field $field";
            }
        }
    }

    private function calculateCost(DateAvailability $availability): float
    {
        $property = ModelMapper::mapProperty($this->propertyRepository->find($availability->getListingId()));

        $totalDays = $this->toDateTime($availability->getStartDate())->diff($this->toDateTime($availability->getEndDate()));

        return $property->getPrice() * $totalDays->d;

    }

    private function toDateTime(string $date): ?DateTime
    {
        try {
            return new DateTime($date);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findAllByUser(string $userId): array
    {
        $reservation = $this->reservationRepository->findByUser($userId);


        if ($reservation != null) {
            $reserves = array();

            foreach ($reservation as $rs) {
                $res = ModelMapper::mapReservation($rs);

                $user = $this->userRepository->find($res->getUserId());
                $list = ModelMapper::mapProperty($this->propertyRepository->find($res->getListingId()));
                $detailed = new ReservationDetailed($res, $user, $list);
                $reserves[] = $detailed;
            }
            header('HTTP/1.1 200 OK');
            return [
                'success' => true,
                'message' => 'Reservation created successfuly',
                'reservations' => $reserves,
            ];
        }

        header('HTTP/1.1 404 Not Found');
        return [
            'success' => false,
            'message' => 'Checkin and checkout dates are not available',
        ];

    }
}
