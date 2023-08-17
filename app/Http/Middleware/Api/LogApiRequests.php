<?php

namespace App\Http\Middleware\Api;

use App\Exceptions\CustomGenericException;
use App\Model\ApiLog;
use Closure;
use Illuminate\Http\Request;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $errorLogs = ErrorLog::orderBy('created_at', 'desc')->paginate(20);
dd($errorLogs);
            $response = $next($request);
        } catch (\Exception $e) {
            throw new CustomGenericException($e->getMessage());
        }
        // Log data
        $log = new ApiLog();
        $log->ip = $request->ip();
        $log->log_date = now();
        $log->user_agent = $request->userAgent();
        $log->request_endpoint = $request->fullUrl();
        $log->response_code = $response->status();
        $log->response_time = microtime(true) - LARAVEL_START;
        $log->request_body = json_encode($request->all());
        $log->response_body = $response->getContent();
        $log->save();

        return $response;
    }
}
