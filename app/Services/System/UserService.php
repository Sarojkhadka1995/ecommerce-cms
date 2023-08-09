<?php

namespace App\Services\System;

use App\Events\UserCreated;
use App\Exceptions\CustomGenericException;
use App\Exceptions\EncryptedPayloadException;
use App\Exceptions\NotDeletableException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\RoleNotChangeableException;
use App\Repositories\System\RoleRepository;
use App\Repositories\System\UserRepository;
use App\Services\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends Service
{
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        return $this->userRepository->getAllData($data);
    }

    public function indexPageData($request)
    {
        return [
            'items' => $this->getAllData($request),
            'roles' => $this->roleRepository->getRoles(),
        ];
    }

    public function createPageData($request)
    {
        return [
            'roles' => $this->roleRepository->getRoles(),
        ];
    }

    public function store($request)
    {
        \DB::transaction(function () use ($request) {
            $data = $request->except('_token');
            if ($request->set_password_status == '1') {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            $token = $this->userRepository->generateToken(24);
            $data['token'] = $token;
            $user = $this->userRepository->create($data);
            $user->roles()->attach($request->role_id);

            try {
                event(new UserCreated($user, $token));
                return $user;
            } catch (\Exception $e) {
                \Log::error('User creation failed: ' . $e->getMessage());
                \DB::rollBack();
                return throw new CustomGenericException('User creation failed. Please try again.');
            }
        });
    }

    public function editPageData($request, $id)
    {
        $user = $this->userRepository->itemByIdentifier($id);
        return [
            'item' => $user,
            'roles' => $this->roleRepository->getRoles(),
            'roleUsers' => $this->roleRepository->getByRolePivotRoleUser($id),
        ];
    }

    public function update($request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token');
            $user = $this->userRepository->itemByIdentifier($id);
            if (isset($request->role_id) && ($user->id == 1 && $request->role_id != 1)) {
                throw new RoleNotChangeableException('The role of the specific user cannot be changed.');
            }
            unset($data['role_id']);
            $this->userRepository->update($user, $data);
            $user->roles()->sync($request->role_id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CustomGenericException($e->getMessage());
        }
    }

    public function delete($request, $id)
    {
        if ($id == 1) {
            throw new NotDeletableException();
        }
        $user = $this->userRepository->itemByIdentifier($id);
        $user->roles()->detach();

        $this->userRepository->delete($request, $id);
        return $user;

    }

    public function findByEmailAndToken($email, $token)
    {
        try {
            $decryptedToken = decrypt($token);
        } catch (\Exception $e) {
            throw new EncryptedPayloadException('Invalid encrypted data');
        }
        $user = $this->userRepository->findByEmailAndToken($email, $decryptedToken);

        if (!isset($user)) {
            throw new ResourceNotFoundException("User doesn't exist in our system.");
        }

        $checkExpiryDate = now()->format('Y-m-d H:i:s') <= $user->expiry_datetime;

        if (!$checkExpiryDate) {
            throw new ResourceNotFoundException("The provided link has expired.");
        }
        return $user;
    }

    public function findByEmail($email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function resetPassword($request)
    {
        return $this->userRepository->resetPassword($request);
    }

    public function generateToken($length)
    {
        return $this->userRepository->generateToken($length);
    }
}
