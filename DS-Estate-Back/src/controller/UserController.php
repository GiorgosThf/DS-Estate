<?php

namespace src\controller;

use GuzzleHttp\Psr7\ServerRequest;
use src\service\UserService;

class UserController extends BaseController
{
    private UserService $userService;
    protected ServerRequest $request;


    public function __construct(ServerRequest $request)
    {
        parent::__construct($request);
        $this->userService = new UserService();
    }

    public function processRequest(): void
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                $this->handleGetRequest();
                break;
            default:
                $this->notFoundResponse();
                break;
        }
    }

    protected function handleGetRequest(): void
    {
        $id = $this->extractUserIdFromRequest();

        $response = $id ? $this->userService->find($id) : $this->userService->findAll();

        echo json_encode($response);
    }

    private function extractUserIdFromRequest(): ?int
    {
        $queryParams = $this->request->getQueryParams();
        return isset($queryParams['id']) ? (int)$queryParams['id'] : null;
    }

    private function notFoundResponse(): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Endpoint not found"]);
    }
}
