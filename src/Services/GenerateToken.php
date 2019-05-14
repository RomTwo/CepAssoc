<?php

namespace App\Services;

use Firebase\JWT\JWT;

class GenerateToken
{
    /**
     * Generate a JWT token
     *
     * @return string
     */
    public function generateToken() : string
    {
        $payload = array(
            'iat' => time(),
            'exp' => time() + 1800
        );
        $token = JWT::encode($payload, $_ENV['PRIVATE_KEY'], $_ENV['ALG']);

        return $token;
    }
}