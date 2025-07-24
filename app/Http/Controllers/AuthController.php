<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;


class AuthController extends Controller
{
    private $secret = 'mas-fiq';

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = DB::table('users')->where('username', $username)->where('password', $password)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $payload = [
            'sub' => $user->id,
            'username' => $user->username,
            'iat' => time(),
            'exp' => time() + (60 * 60), // 1 jam
            'iss' => 'api-rqci' ,

        ];

        $token = JWT::encode($payload, $this->secret, 'HS256');

        return response()->json(['token' => $token]);
    }

    public function me(Request $request)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, $this->secret, ['HS256']);
            return response()->json([
                'id' => $decoded->sub,
                'username' => $decoded->username,
                'iss' => $decoded->iss ,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }
}
