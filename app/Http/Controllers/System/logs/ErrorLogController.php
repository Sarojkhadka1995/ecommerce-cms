<?php

namespace App\Http\Controllers\System\logs;

use App\Http\Controllers\System\ResourceController;
use App\Services\System\ApiLogService;
use App\Services\System\ErrorLogService;

class ErrorLogController extends ResourceController
{
    public function __construct(private readonly ErrorLogService $logService)
    {
        parent::__construct($logService);
    }

    public function moduleName()
    {
        return 'error-logs';
    }

    public function viewFolder()
    {
        return 'system.errorLog';
    }
}
