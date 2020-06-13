<?php

namespace App\Http\Middleware;

use App\Helpers\Response;
use Closure;


class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = new Response();
        if ($request->isAdmin && $request->isAdmin === "Yes") {

            return $next($request);
        }

        $msg = $response->response(401);
        return response()->json($msg);
    }
}
