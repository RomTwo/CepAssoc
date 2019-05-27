<?php

namespace App\Services;

use App\Entity\Account;
use Firebase\JWT\JWT;

class GenerateToken
{
    /**
     * Generate a JWT token (this token is valid during 30 minutes (1800 secondes)
     *
     * @return string
     */
    public function generateJwtToken(): string
    {
        $payload = array(
            'iat' => time(),
            'exp' => time() + 1800
        );
        $token = JWT::encode($payload, $_ENV['PRIVATE_KEY'], $_ENV['ALG']);

        return $token;
    }

    public function generateCustomToken(int $during)
    {
        $payload = array(
            'iat' => time(),
            'exp' => time() + $during
        );
        $token = JWT::encode($payload, $_ENV['PRIVATE_KEY'], $_ENV['ALG']);
        return $token;
    }
}