<?php

namespace App\Http\Controllers\system\user;

use App\Http\Controllers\system\ResourceController;
use App\Services\RoleService;

class RoleController extends ResourceController
{
    public function __construct(RoleService $roleService)
    {
        parent::__construct($roleService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\system\roleRequest';
    }
    
    public function moduleName()
    {
        return 'roles';
    }

    public function viewFolder()
    {
        return 'system.role';
    }
}
