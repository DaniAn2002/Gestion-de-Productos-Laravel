<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader) {
            return response()->json([
                'status' => false,
                'message' => 'Unathorized',
                'data' => 'No data'
            ], 401);
        }

        $token = explode(' ', $authHeader)[1];
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Unathorized',
                'data' => 'No data'
            ], 401);
        }
        try {
            $user = JWTAuth::parseToken()->authenticate($token);
            return $next($request);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token',
                'data' => 'No data'
            ], 401);
        }
    }
}
