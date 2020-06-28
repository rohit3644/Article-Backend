<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

// this class is used to log all the requests and responses
// coming to the System in local Environment
class LogRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    // this function logs all the requests and responses in local
    // environment 
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (app()->environment('local')) {
            $log = [
                'URI' => $request->getUri(),
                'METHOD' => $request->getMethod(),
                'REQUEST_BODY' => $request->all(),
                'RESPONSE' => $response->getContent(),
            ];
            Log::info(json_encode($log));
        }
        return $response;
    }
}
