<?php

namespace src\controller;

use GuzzleHttp\Psr7\ServerRequest;
use src\service\ReservationService;
use src\utils\CustomLogger;

class ReservationController extends BaseController
{
    private ReservationService $reservationService;
    protected ServerRequest $request;
    private CustomLogger $customLogger;


    public function __construct(ServerRequest $request)
    {
        parent::__construct($request);
        $this->reservationService = new ReservationService();
        $this->customLogger = new CustomLogger();
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
        $this->customLogger->info(json_encode($params));
        $response = null;
        if (!isset($params['id']) && !isset($params['userId'])) {
            $response = $this->reservationService->isAvailable($this->extractQueryParams());
        } elseif (isset($params['userId'])) {
            $response = $this->reservationService->findAllByUser($params['userId']);
        } elseif (isset($params['id'])) {
            $response = $this->reservationService->find($params['id']);
        }

        echo json_encode($response);
    }

    protected function handlePostRequest(): void
    {
        $input = $this->extractInputFromRequest();
        $response = $this->reservationService->create($input);

        echo json_encode($response);
    }

    private function extractUserIdFromRequest(): ?int
    {
        $queryParams = $this->request->getQueryParams();
        return isset($queryParams['userId']) ? (int)$queryParams['userId'] : null;
    }

    private function extractInputFromRequest(): ?array
    {
        $requestData = $this->request->getBody()->getContents();

        $this->customLogger->info($requestData);

        return json_decode($requestData, true);
    }

    private function notFoundResponse(): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Endpoint not found"]);
    }

    private function extractQueryParams(): array
    {
        $queryParams = $this->request->getQueryParams();
        $this->customLogger->info(json_encode($queryParams));

        return $queryParams;

    }
}
