<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\AuthToken;

class AuthKey
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
        $authToken = new AuthToken();
        $token = $request->header('authorization');
        $id = intval(substr($token, 65));
        if ($authToken->isValid($token, $id)) {
            return $next($request);
        }
        return response()->json([
            "message" => "Access Denied",
            "code" => 401,
        ]);
    }
}
