<?php

namespace Config;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;

class PHPJWT
{
    private string $secret;
    private string $algorithm;
    private int $expirationTime;

    public function __construct()
    {
        $this->secret = $_ENV['JWT_SECRET'];
        $this->algorithm = $_ENV['JWT_ALGORITHM'];
        $this->expirationTime = (int) $_ENV['JWT_EXPIRATION_TIME'];
    }

    public function generateToken(array $data): string
    {
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify("+{$this->expirationTime} seconds")->getTimestamp();
        $payload = array_merge($data, [
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $expire,
        ]);

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    public function validateToken(string $token): object
    {
        return JWT::decode($token, new Key($this->secret, $this->algorithm));
    }
}