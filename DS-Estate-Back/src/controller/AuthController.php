<?php

namespace src\controller;

use GuzzleHttp\Psr7\ServerRequest;
use src\service\AuthService;

class AuthController extends BaseController
{
    private AuthService $authService;
    protected ServerRequest $serverRequest;


    public function __construct(ServerRequest $serverRequest)
    {
        parent::__construct($serverRequest);
        $this->serverRequest = $serverRequest;
        $this->authService = new AuthService();
    }

    public function processRequest(): void
    {
        switch ($this->request->getUri()->getPath()) {
            case '/login':
                $this->handleGetRequest();
                break;
            case '/register':
                $this->handlePostRequest();
                break;
            default:
                $this->notFoundResponse();
                break;
        }
    }

    private function notFoundResponse(): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Endpoint not found"]);
    }

    public function handleGetRequest(): void
    {
        $credentials = $this->extractInputFromRequest();
        $response = $this->authService->login($credentials);

        echo json_encode($response);
    }

    public function handlePostRequest(): void
    {
        $input = $this->extractInputFromRequest();
        $response = $this->authService->register($input);

        echo json_encode($response);
    }

    private function extractInputFromRequest(): ?array
    {
        $requestData = $this->request->getBody()->getContents();
        return json_decode($requestData, true);
    }
}
