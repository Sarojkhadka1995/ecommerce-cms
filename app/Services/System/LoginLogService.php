<?php

namespace App\Services\System;

use App\Repositories\System\LoginLogRepository;
use App\Services\Service;

class LoginLogService extends Service
{
    public function __construct(LoginLogRepository $loginLogRepository)
    {
        parent::__construct($loginLogRepository);
    }
}
