<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

   
    use JWTAuth;
    use Exception;
    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json(['message' => 'Token is Invalid','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))], 401);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json(['message' => 'Token is Expired','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))], 401);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException){
                    return response()->json(['message' => 'Token is Blacklisted','success' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))], 401);
                }else{
                    return response()->json(['message' => 'Authorization Token not found','status' => false,'data' => json_decode(json_encode([], JSON_FORCE_OBJECT))], 401);
                }
            }
            return $next($request);
    }
}
