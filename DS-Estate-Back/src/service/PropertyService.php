<?php

namespace src\service;

use PDOException;
use src\repository\PropertyRepository;
use src\utils\ModelMapper;

class PropertyService
{
    private PropertyRepository $propertyRepository;

    public function __construct()
    {
        $this->propertyRepository = new PropertyRepository();
    }

    public function findAll(): array
    {
        try {
            $props = array();
            $properties = $this->propertyRepository->findAll();
            foreach ($properties as $property) {
                $props[] = ModelMapper::mapProperty($property);
            }
            header('HTTP/1.1 200 Ok');
            return [
                'success' => true,
                'message' => 'Properties fetched',
                'properties' => $props,
            ];
        } catch (PDOException $e) {
            header('HTTP/1.1 500 Internal Server Error');
            return [
                'success' => false,
                'message' => 'Issue while retrieving all properties ' . $e->getMessage()
            ];
        }
    }

    public function findAllByUser($owner_id): array
    {
        try {
            $props = array();
            $properties = $this->propertyRepository->findAllByUser($owner_id);
            foreach ($properties as $property) {
                $props[] = ModelMapper::mapProperty($property);
            }
            header('HTTP/1.1 200 Ok');
            return [
                'success' => true,
                'message' => 'Properties fetched',
                'properties' => $props,
            ];
        } catch (PDOException $e) {
            header('HTTP/1.1 500 Internal Server Error');
            return [
                'success' => false,
                'message' => 'Issue while retrieving all properties ' . $e->getMessage()
            ];
        }
    }

    public function find($id): array
    {
        try {
            $property = $this->propertyRepository->find($id);
            header('HTTP/1.1 200 Ok');
            return [
                'success' => true,
                'message' => 'Property fetched',
                'property' => ModelMapper::mapProperty($property),
            ];
        } catch (PDOException $e) {

            header('HTTP/1.1 500 Internal Server Error');
            return [
                'success' => false,
                'message' => 'Issue while retrieving property ' . $e->getMessage(),
            ];
        }
    }

    public function create(array $propertyData): array
    {
        try {
            $validation = $this->validateData($propertyData);

            $propertyCreated = $this->propertyRepository->create($propertyData);

            return ['success' => $validation['valid'] && $propertyCreated,
                'message' => $propertyCreated
                    ? 'Property created successfully' : $validation['error']];

        } catch (PDOException $e) {
            header('HTTP/1.1 500 Internal Server Error');
            return [
                'success' => false,
                'message' => 'Issue while creating property ' . $e->getMessage(),
            ];
        }
    }

    private function validateData(array $propertyData): array
    {
        $requiredFields = ['title', 'photo_url', 'area', 'number_of_rooms', 'price_per_night'];
        foreach ($requiredFields as $field) {
            if (!isset($propertyData[$field])) {
                return ['valid' => false, 'error' =>
                    'Missing required field: ' . $field];
            }
        }
        return ['valid' => true];
    }
}
