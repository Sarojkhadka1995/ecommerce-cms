<?php

namespace App\Services\System;

use App\Repositories\System\ErrorLogRepository;
use App\Services\Service;

class ErrorLogService extends Service
{
    public function __construct(ErrorLogRepository $logRepository)
    {
        $this->repository = $logRepository;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        return $this->repository->getAllData($data, [], $pagination = true);
    }
}
