<?php

namespace App\Services\Api;

use App\Model\FrontendUser;

class SocialService
{
    public function __construct(FrontendUser $user)
    {
        $this->user = $user;
    }

    public function setOrGetUser($userData)
    {
        $existingUser = $this->user->where([
            ['email', $userData['email']],
            ['provider',$userData['provider']],
            ['provider_user_id', $userData['user_id']]
        ])->first();
        if(empty($existingUser)){
            $parsedUserData = $this->parsedUserData($userData);
            return $this->user->create($parsedUserData);
        }
        else return $existingUser;
    }

    public function parsedUserData($data){
        return [
            'name' => $data['name'] ?? null,
            'username' => $data['given_name'] ?? null,
            'email' => $data['email'] ?? null,
            'name' => $data['name'] ?? null,
            'provider' => $data['provider'] ?? 'google',
            'provider_user_id' => $data['user_id'] ?? null
        ];
    }
}
