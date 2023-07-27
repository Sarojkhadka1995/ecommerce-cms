<?php

namespace App\Repositories\System;

use App\Interfaces\System\LoginLogInterface;
use App\Model\Loginlogs;
use App\Repositories\Repository;
use Carbon\Carbon;
use Config;

class LoginLogRepository extends Repository implements LoginLogInterface
{
  public function __construct(Loginlogs $loginlogs)
  {
    parent::__construct($loginlogs);
  }
  public function getAllData($data, $selectedColumns = [], $pagination = true)
  {
    $query = $this->query();
    if (count($selectedColumns) > 0) {
      $query->select($selectedColumns);
    }
    if (isset($data->from) && isset($data->to)) {
      $from = Carbon::createFromFormat('Y-m-d H:i:s', $data->from . ' 00:00:00');
      $to = Carbon::createFromFormat('Y-m-d H:i:s', $data->to . ' 23:59:00');
      $query->whereBetween('created_at', [$from, $to]);
    }
    if ($pagination) {
      return $query->orderBy('id', 'DESC')->with('user')->paginate(Config::get('constants.PAGINATION'));
    } else {
      return $query->get();
    }
  }
}
