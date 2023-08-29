<?php

namespace App\Services\System;

use App\Repositories\System\ErrorLogRepository;
use App\Services\Service;

class ErrorLogService extends Service
{
    public function __construct(ErrorLogRepository $logRepository)
    {
        parent::__construct($logRepository);
    }
}
