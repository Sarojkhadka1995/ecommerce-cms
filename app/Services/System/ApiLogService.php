<?php

namespace App\Services\System;

use App\Repositories\System\ApiLogRepository;
use App\Repositories\System\LogRepository;
use App\Services\Service;

class ApiLogService extends Service
{
    public function __construct(ApiLogRepository $logRepository)
    {
        parent::__construct($logRepository);
    }
}
