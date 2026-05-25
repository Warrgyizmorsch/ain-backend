<?php
namespace App\Services;

class TokenService
{
    public static function generateToken()
    {
        // Use the secret from the environment variable
        return env('LL_API_SECRET');
    }
}
