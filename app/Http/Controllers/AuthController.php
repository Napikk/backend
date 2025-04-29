<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'min:3', 'max:255'],
            'email' =>[
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => ['required','string','min:8'],
        ]);

        $user = User::create([
            'name'  =>$request->name,
            'email'  =>$request->email,
            'password'  =>Hash::make($request->password),
            'role'  => 'karyawan',
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil!',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah!',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'=>'Login berhasil!',
            'token' => $token,
            'token_type' => 'Bearer',
            'user'  => [
                'id' => $user->id,
                'name' =>$user->name,
                'email' =>$user->email,
                'role' =>$user->role,
            ]
        ]);
        
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' =>'Logout berhasil'
        ]);
    }
}
