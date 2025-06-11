<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function add(Request $request)
    {
        try{
            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'nullable|string|max:50',
                'sekolah' => 'nullable|integer|exists:sekolah,id',
                'kelas' => 'nullable|integer|exists:kelas,id',
            ]);
            if ($validate) {
                $user = new User();
                $user->name = $request->name;
                $user->username = $request->username;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->role = $request->role;
                $user->sekolah = $request->sekolah;
                $user->kelas = $request->kelas;
                $user->save();

                return response()->json(['status' => true, 'message' => 'User created successfully', 'data' => $user], 201);
            }

        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    //
    public function index(Request $request)
    {
        try{
            if($request->id){
                $user = User::find($request->id);
                if($user){
                    return response()->json(['status' => true, 'data' => $user], 200);
                }
            }
            $users = User::with('sekolah', 'kelas')->get();
            return response()->json(['status' => true, 'data' => $users], 200);
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try{
            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'required|string|min:8|confirmed',
                'role' => 'nullable|string|max:50',
                'sekolah' => 'required|integer|exists:sekolah,id',
                'kelas' => 'required|integer|exists:kelas,id',
            ]);

            if ($validate) {
                $user = User::find($id);
                if ($user) {
                    $user->name = $request->name ?? $user->name;
                    $user->username = $request->username ?? $user->username;
                    $user->email = $request->email ?? $user->email;
                    if ($request->password) {
                        $user->password = Hash::make($request->password);
                    }
                    $user->role = $request->role ?? $user->role;
                    $user->sekolah = $request->sekolah ?? $user->sekolah;
                    $user->kelas = $request->kelas ?? $user->kelas;
                    $user->save();

                    return response()->json(['status' => true, 'message' => 'User updated successfully', 'data' => $user], 200);
                }
            }
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['status' => true, 'message' => 'User deleted successfully'], 200);
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    

}
