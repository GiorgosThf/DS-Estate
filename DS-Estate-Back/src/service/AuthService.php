<?php

namespace src\service;

use PDOException;
use src\model\JwtToken;
use src\model\User;
use src\repository\AuthRepository;
use src\repository\UserRepository;
use src\utils\ModelMapper;

class AuthService
{
    private AuthRepository $authRepository;
    private JwtToken $jwtToken;

    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    public function login(array $credentials): array
    {
        try {
            $user = $this->authRepository->findByUsername($credentials['username']);

            if ($user && $credentials['password'] == $user['password']) {
                $this->jwtToken = new JwtToken($credentials['username']);

                header('HTTP/1.1 200 Ok');
                return [
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => ModelMapper::mapUser($user),
                    'token' => $this->jwtToken->toJwt(),
                ];
            } else {
                header('HTTP/1.1 400 Not Found');
                return [
                    'success' => false,
                    'message' => 'Invalid username or password',
                ];
            }

        } catch (PDOException $e) {
            header('HTTP/1.1 500 Internal Server Error');
            return [
                'success' => false,
                'message' => 'Login Failed !' . $e->getMessage(),
            ];
        }
    }

    public function register(array $input): array
    {
        $user = ModelMapper::mapUser($input);

        try {

            $newUserId = $this->authRepository->create($user);
            $user->setId($newUserId);
            $this->jwtToken = new JwtToken($user->getUsername());

            header('HTTP/1.1 200 Ok');
            return [
                'success' => true,
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $this->jwtToken->toJwt(),
            ];
        } catch (PDOException $e) {

            header('HTTP/1.1 500 Internal Server Error');
            return [
                'success' => false,
                'message' => 'Registration failed ' . $e->getMessage(),
            ];
        }
    }
}
