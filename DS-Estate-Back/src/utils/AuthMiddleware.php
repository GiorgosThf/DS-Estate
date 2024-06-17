<?php

namespace src\utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class AuthMiddleware
{
    private string $secretKey = 'ds-estate-key';

    public function __invoke($request, $response, $next)
    {
        $authHeader = $request->getHeader('Authorization');
        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader[0]);
            try {
                $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
                // Token is valid
                $request = $request->withAttribute('user', $decoded->sub);
                return $next($request, $response);
            } catch (\Exception $e) {
                return $this->unauthorizedResponse();
            }
        } else {
            return $this->unauthorizedResponse();
        }
    }

    private function unauthorizedResponse(): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode(["message" => 'Unauthorized: Please Login to continue', "success" => false, "isLoggedIn" => false]));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
