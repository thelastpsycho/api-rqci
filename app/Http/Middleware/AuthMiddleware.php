<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Exception;

class AuthMiddleware
{
    // private $secret = 'mas-fiq';    
    public function handle($request, Closure $next)
    {
        // $token = null;
        // Ambil token dari header Authorization
        $authHeader = $request->header('Authorization');
        if (!$authHeader && !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            \Log::error('token noe found');
            // $token = $matches[1];
            return response()->json(['error' => 'token gak ada'], 401)
        }

        // if (!$token) {
        //     return response()->json(['error' => 'Token not found please login'], 401);
        // }
        $token = $matches[1];
        $secret = env('JWT_SECRET', 'fallback-secret'); // ingat update .env

        try {
            // $decoded = JWT::decode($token, $this->secret, ['HS256']);
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            // Tambahkan user ke request jika perlu
            $request->auth = $decoded;
            return $next($request);
        } catch (Exception $e) {
            \Log::error('Auth Middleware: Token validation failed - ' . $e->getMessage());
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        // return $next($request);
    }
}
