<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\AuthToken;
use App\Helpers\Response;

// this class is used for authorizing the api token send from client
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
        $response = new Response();
        $authToken = new AuthToken();
        $token = $request->header('authorization');
        $id = intval(substr($token, 65));
        if ($authToken->isValid($token, $id)) {
            return $next($request);
        }
        $msg = $response->response(404);
        return response()->json($msg);
    }
}
