<?php

namespace App\Services\System;

use App\Exceptions\CustomGenericException;
use App\Exceptions\NotDeletableException;
use App\Repositories\System\RoleRepository;
use App\Repositories\System\UserRepository;
use App\Services\Service;

class RoleService extends Service
{
    public function __construct(RoleRepository $roleRepository, UserRepository $userRepository)
    {
        parent::__construct($roleRepository);
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function indexPageData($request)
    {
        return [
            'items' => $this->repository->getAllData($request),
            'roles' => $this->roleRepository->getRoles()
        ];
    }

    public function store($request)
    {
        $data = $request->except('_token');
        $data['permissions'] = $this->roleRepository->mapPermission($request->permissions);

        return $this->roleRepository->create($data);
    }

    public function editPageData($request, $id)
    {
        $role = $this->roleRepository->itemByIdentifier($id);

        return [
            'item' => $role,
        ];
    }

    public function update($request, $id)
    {
        $data = $request->except('_token');
        $data['permissions'] = $this->roleRepository->mapPermission($data['permissions']);
        $checkRoleUsers = $this->userRepository->getByRolePivotRoleUser($id);

        $this->roleRepository->update($data, $id);
        $role = $this->roleRepository->itemByIdentifier($id);
        if ($checkRoleUsers != null || isset($checkRoleUsers)) {
            foreach ($checkRoleUsers as $user) {
                if (getRoleCache($user) != null) {
                    clearRoleCache($user);
                    setRoleCache($user);
                }
            }
        }

        return $role;
    }

    public function delete($request, $id)
    {
        try {
            if ($request->role_id == $id) {
                throw new CustomGenericException('The role that currently exist and the ones that have been modified must not be identical.');
            }
            if ($request->role_id) {
                $this->userRepository->bulkUpdateUserByRole($id, $request->role_id);
            }
            return $this->roleRepository->delete($request, $id);
        } catch (\Exception $e) {
            throw new CustomGenericException($e->getMessage());
        }
    }
}
