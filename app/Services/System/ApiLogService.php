<?php

namespace App\Services\System;

use App\Repositories\System\ApiLogRepository;
use App\Repositories\System\LogRepository;
use App\Services\Service;

class ApiLogService extends Service
{
    public function __construct(ApiLogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        return $this->logRepository->getAllData($data);
    }

    public function indexPageData($data)
    {
        return [
            'items' => $this->getAllData($data),
        ];
    }
}
