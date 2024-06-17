<?php

namespace src\model;

use Firebase\JWT\JWT;

class JwtToken
{
    private string $issuedFrom;
    private int|float $issuedAt;
    private int|float $expiresAt;
    private string $subject;
    private string $secretKey = 'ds-estate-key';
    private string $algorithm = 'HS256';

    public function __construct($subject)
    {
        $this->subject = $subject;
        $this->issuedFrom = 'ds-estate.com';
        $this->issuedAt = time();
        $this->expiresAt = time() + (60 * 60);
    }

    public function newInstance($subject): JwtToken
    {
        return new JwtToken($subject);
    }

    public function toJwt(): string
    {
        return JWT::encode(
            [
                'iss' => $this->issuedFrom,
                'iat' => $this->issuedAt,
                'exp' => $this->expiresAt,
                'sub' => $this->subject
            ],
            $this->secretKey,
            $this->algorithm
        );
    }
}
