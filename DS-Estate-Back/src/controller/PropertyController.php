<?php

namespace src\controller;

use GuzzleHttp\Psr7\ServerRequest;
use src\service\PropertyService;


class PropertyController extends BaseController
{
    private PropertyService $propertyService;
    protected ServerRequest $request;


    public function __construct(ServerRequest $request)
    {
        parent::__construct($request);
        $this->propertyService = new PropertyService();
    }

    public function processRequest(): void
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                $this->handleGetRequest();
                break;
            case 'POST':
                $this->handlePostRequest();
                break;
            default:
                $this->notFoundResponse();
                break;
        }
    }

    protected function handleGetRequest(): void
    {
        $params = $this->request->getQueryParams();
        $response = null;
        if (!isset($params['id']) && !isset($params['owner_id'])) {
            $response = $this->propertyService->findAll();
        } elseif (isset($params['owner_id'])) {
            $response = $this->propertyService->findAllByUser($params['owner_id']);
        } elseif (isset($params['id'])) {
            $response = $this->propertyService->find($params['id']);
        }

        echo json_encode($response);
    }

    protected function handlePostRequest(): void
    {
        $input = $this->extractInputFromRequest();
        $response = $this->propertyService->create($input);

        echo json_encode($response);
    }

    private function extractIdFromRequest(): ?int
    {
        $queryParams = $this->request->getQueryParams();
        return isset($queryParams['id']) ? (int)$queryParams['id'] : null;
    }

    private function extractInputFromRequest(): ?array
    {
        $requestData = $this->request->getBody()->getContents();
        return json_decode($requestData, true);
    }

    private function notFoundResponse(): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Endpoint not found"]);
    }


}
