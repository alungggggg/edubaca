<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use App\Models\SekolahModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        try{

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
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    
    }

    public function logout(Request $request){
        try{
            $user = $request->user();
            if ($user && method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }
            return response()->json([
                'status' => true,
                'message' => 'Berhasil logout'
            ], 200);

        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function profile(Request $request){
        try{
            $profile= User::with(['sekolah', 'kelas'])->find($request->user()->id);
            return response()->json([
                'status' => true,
                'data' => $profile
            ], 200);
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request){
        try{
            $user = $request->user();
            $user->update($request->only(['name']));
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => $user
            ], 200);
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $request){
        
        try{
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);
            
            $user = $request->user();
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['status' => false, 'message' => 'Current password is incorrect'], 400);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['status' => true, 'message' => 'Password changed successfully'], 200);

        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
        
    }

    public function register(Request $request){
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'sekolah' => 'required|integer|exists:sekolah,id',
                'kelas' => 'required|integer|exists:kelas,id',
            ]);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->role = "SISWA";
            $user->password = Hash::make($request->password);
            $user->sekolah_id = $request->sekolah;
            $user->kelas_id = $request->kelas;
            $user->save();

            return response()->json(['status' => true, 'message' => 'User registered successfully'], 201);

        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
