<?php

namespace App\Services\System;

use App\Exceptions\CustomGenericException;
use App\Exceptions\UnauthorizedException;
use App\Repositories\System\UserRepository;
use App\Services\Service;
use App\User;
use Illuminate\Support\Facades\Hash;


class ProfileService extends Service
{
    public function __construct(UserRepository $user)
    {
        $this->repository = $user;
    }

    public function indexPageData($data)
    {
        return [
            'item' => $this->repository->itemByIdentifier(authUser()->id),
        ];
    }

    public function update($request, $id)
    {
        try {
            if (authUser()->id != $id) {
                throw new UnauthorizedException('Unauthorized action performed.');
            }
            $data = $request->only('password');
            $user = $this->itemByIdentifier($id);
            $logMsg = "New Password was <strong>created</strong> by {$user->name}";
            storeLog($user, $logMsg);

            return $user->update([
                'password' => Hash::make($data['password']),
            ]);
        } catch (\Exception $e) {
            throw new CustomGenericException($e->getMessage());
        }
    }
}
