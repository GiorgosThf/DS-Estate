<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use src\controller\AuthController;
use src\controller\PropertyController;
use src\controller\ReservationController;
use src\controller\UserController;
use src\utils\AuthMiddleware;

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle pre-flight option requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$authMiddleware = new AuthMiddleware();
$request = ServerRequest::fromGlobals();
$path = $request->getUri()->getPath();
$method = $request->getMethod();
$response = new Response();

$protectedRoutes = [
    '/user',
    '/property',
    '/reservation'
];

function handleRequest($controllerClass, ServerRequest $request): void
{
    $controller = new $controllerClass($request);
    $controller->processRequest();
}

if (in_array($path, $protectedRoutes)) {
    $response = $authMiddleware($request, $response, function ($req, $res) use ($path) {
        switch ($path) {
            case '/user':
                handleRequest(UserController::class, $req);
                break;
            case '/property':
                handleRequest(PropertyController::class, $req);
                break;
            case '/reservation':
                handleRequest(ReservationController::class, $req);
                break;
            default:
                $res->getBody()->write(json_encode(["message" => "Endpoint does not exist"]));
                return $res->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        return $res;
    });
} else {
    switch ($path) {
        case '/feed':
            handleRequest(PropertyController::class, $request);
            break;
        case '/register':
        case '/login':
            handleRequest(AuthController::class, $request);
            break;
        default:
            $response->getBody()->write(json_encode(["message" => "Endpoint does not exist"]));
            $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
}

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();

