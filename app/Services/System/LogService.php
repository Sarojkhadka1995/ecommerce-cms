<?php

namespace App\Services\System;

use App\Repositories\System\LogRepository;
use App\Services\Service;

class LogService extends Service
{
    public function __construct(LogRepository $logRepository)
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
