<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Exception;

class AuthMiddleware
{
    private $secret = 'mas-fiq';
    public function handle($request, Closure $next)
    {
        $token = null;

        // Ambil token dari header Authorization
        $authHeader = $request->header('Authorization');
        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (!$token) {
            return response()->json(['error' => 'Token not found please login'], 401);
        }

        try {
            $decoded = JWT::decode($token, $this->secret, ['HS256']);
            // Tambahkan user ke request jika perlu
            $request->auth = $decoded;
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        return $next($request);
    }
}
