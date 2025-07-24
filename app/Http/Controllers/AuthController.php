<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthController extends Controller
{
    // private $secret = 'mas-fiq';

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (!$username || !$Password) {
            return response()->json->(['error' => 'username password required bro'], 400)
        }

        //fetch dari database
        $user = DB::table('users')->where('username', $username)->first();
        //$user = DB::table('users')->where('username', $username)->where('password', $password)->first();

        //verifikasi user
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        // if (!$user) {
        //     return response()->json(['error' => 'Invalid credentials'], 401);
        // }

        $payload = [
            'sub' => $user->id,
            'username' => $user->username,
            'iat' => time(),
            'exp' => time() + (60 * 60), // 1 jam
            'iss' => 'api-rqci' ,

        ];

        $secret = env('JWT_SECRET', 'fallback-secret'); // taruh di .env itu yang mas-fiq
        $token = JWT::encode($payload, $this->secret, 'HS256');

        return response()->json(['token' => $token], 200);
    }

    public function me(Request $request)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $token = $matches[1];
        $secret = env('JWT_SECRET', 'fallback-secret')

        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            // $decoded = JWT::decode($token, $this->secret, ['HS256']);
            return response()->json([
                'id' => $decoded->sub,
                'username' => $decoded->username,
                'iss' => $decoded->iss ,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Toket validation gagal bro: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }
}
