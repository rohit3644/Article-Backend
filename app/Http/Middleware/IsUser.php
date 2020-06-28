<?php

namespace App\Http\Middleware;

use App\Helpers\Response;
use Closure;

// this class checks if the request is send by user only
class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // this function checks if request has isAdmin and if isAdmin === 'No'
    public function handle($request, Closure $next)
    {
        $response = new Response();
        if ($request->isAdmin && $request->isAdmin === 'No') {

            return $next($request);
        }

        $msg = $response->response(401);
        return response()->json($msg);
    }
}
