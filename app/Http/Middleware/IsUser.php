<?php

namespace App\Http\Middleware;

use Closure;

class IsUser
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
        if (!$request->isAdmin || $request->isAdmin === "No") {

            return $next($request);
        }

        return response()->json([
            "message" => "Sorry, you don't have access to this functionality",
            "code" => 201,
        ]);
    }
}
