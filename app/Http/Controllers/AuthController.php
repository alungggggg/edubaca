<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
    $request->validate([
        'EmailOrUsername' => 'required|string',
        'password' => 'required|string',
    ]);

    $loginField = filter_var($request->EmailOrUsername, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $credentials = [
        $loginField => $request->EmailOrUsername,
        'password' => $request->password,
    ];

        if (Auth::attempt($credentials)) {
            $userData["token"] = $request
                ->user()
                ->createToken('auth_token',['*'],now()->addDay(), $request->user()->uuid )->plainTextToken;
            $userData["name"] = $request->user()->name;
            return response()->json(['status' => true, "data" => $userData], 200);
        }
        return response()->json(["status" => false, 'message' => 'Bad Request'], 400);
    }

    public function logout(Request $request){
        $user = $request->user();
        if ($user && method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }
        return response()->json(['message' => 'Logged out'], 200);
    }
}
