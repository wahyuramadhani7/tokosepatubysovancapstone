<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Cek apakah email dan password dikirim
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('mobile-app')->plainTextToken;

            // Kirim balik data user dan token
            return response()->json([
                'pesan' => 'Login sukses',
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'token' => $token,
            ]);
        }

        // Kalau gagal, kirim pesan error
        return response()->json([
            'pesan' => 'Email atau password salah',
        ], 401);
    }
}