<?php

namespace App\Services\System;

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
        $checkRoleUsers = $this->userRepository->getByUserRole($id);

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
        $role = $this->roleRepository->itemByIdentifier($id);
        if ($role->users->count() > 0) {
            throw new NotDeletableException('The role is associated to the users.');
        }

        return $this->roleRepository->delete($request, $id);
    }
}
