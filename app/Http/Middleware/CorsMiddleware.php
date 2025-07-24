<?php 

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Exception;

class CorsMiddleware{
    public function handle($request, Closure $next)
{
    $response = $next($request);

    return $response
        ->header('Access-Control-Allow-Origin', '*') // atau ganti * jadi https://site-a.com
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
}
}