<?php

namespace src\controller;

use GuzzleHttp\Psr7\ServerRequest;

abstract class BaseController
{
    protected ServerRequest $request;
    private string $res405 = "HTTP/1.1 405 Method Not Allowed";

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function processRequest(): void
    {
        switch ($this->request['method']) {
            case 'GET':
                $this->handleGetRequest();
                break;
            case 'POST':
                $this->handlePostRequest();
                break;
            case 'PUT':
                $this->handlePutRequest();
                break;
            case 'DELETE':
                $this->handleDeleteRequest();
                break;
            default:
                header($this->res405);
                echo json_encode(["message" => "Method Not Allowed"]);
                break;
        }
    }

    protected function handleGetRequest(): void
    {
        header($this->res405);
        echo json_encode(["message" => "GET Method Not Implemented"]);
    }

    protected function handlePostRequest(): void
    {
        header($this->res405);
        echo json_encode(["message" => "POST Method Not Implemented"]);
    }

    protected function handlePutRequest(): void
    {
        header($this->res405);
        echo json_encode(["message" => "PUT Method Not Implemented"]);
    }

    protected function handleDeleteRequest(): void
    {
        header($this->res405);
        echo json_encode(["message" => "DELETE Method Not Implemented"]);
    }
}

