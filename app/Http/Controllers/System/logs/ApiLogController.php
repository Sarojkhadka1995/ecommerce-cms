<?php

namespace App\Http\Controllers\System\logs;

use App\Http\Controllers\System\ResourceController;
use App\Services\System\ApiLogService;

class ApiLogController extends ResourceController
{
    public function __construct(private readonly ApiLogService $logService)
    {
        parent::__construct($logService);
    }

    public function moduleName()
    {
        return 'api-logs';
    }

    public function viewFolder()
    {
        return 'system.apiLog';
    }
}
