<?php

namespace App\Services;

use App\Services\Service;
use Illuminate\Support\Facades\Http;

class CentralAuthService
{
    public function authenticateUser($username, $password)
    {
        $response = Http::post('https://central-auth-api.com/authenticate', [
            'username' => $username,
            'password' => $password,
        ]);

        return $response->json();
    }
}
