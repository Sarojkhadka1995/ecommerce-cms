<?php

namespace App\Console\Commands;

use App\Model\ApiLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteApiLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:apiLogs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete API Logs';

    public function __construct(ApiLog $apiLog)
    {
        parent::__construct($apiLog);
        $this->apiLog = $apiLog;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $oneYearAgo = Carbon::now()->subYear();

        $this->apiLog->where('created_at', '<', $oneYearAgo)->delete();
        $this->info('Api Logs has been deleted.');
    }
}
