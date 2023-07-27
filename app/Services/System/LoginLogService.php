<?php

namespace App\Services\System;

use App\Repositories\System\LoginLogRepository;
use App\Services\Service;

class LoginLogService extends Service
{
    public function __construct(LoginLogRepository $loginLogRepository)
    {
        $this->loginLogRepository = $loginLogRepository;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        return $this->loginLogRepository->getAllData($data);
    }

    public function indexPageData($data)
    {
        return [
            'items' => $this->getAllData($data),
        ];
    }
}
