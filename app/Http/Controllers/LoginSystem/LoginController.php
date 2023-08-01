<?php

namespace App\Http\Controllers\LoginSystem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Coba otentikasi sebagai pengguna
        if (Auth::attempt($credentials)) {
            // Otentikasi pengguna berhasil
            $user = Auth::user();

            // Periksa status verifikasi email
            if ($user) {
                if ($user->role === 'admin') {
                    $token = $user->createToken('AdminToken')->accessToken;
                    return response()->json(['token' => $token, 'role' => 'admin'], 200);
                } else {
                    $token = $user->createToken('UserToken')->accessToken;
                    return response()->json(['token' => $token, 'role' => 'user'], 200);
                }
            } else {
                Auth::logout();
                return response()->json(['message' => 'Email not verified, Please Verify Your Email'], 401);
            }
        }

        // Otentikasi gagal
        return response()->json(['message' => 'Email or password is incorrect.'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(
            [
                'message' => 'Logged out'
            ]
        );
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Failed to authenticate with Google.');
        }

        // Cek apakah pengguna sudah ada di database berdasarkan email dari Google
        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            Auth::login($existingUser);
        } else {
            $newUser = new User();
            $newUser->full_name = $user->getName();
            $newUser->username = $user->getName();
            $newUser->photo = $user->getAvatar();
            $newUser->email = $user->getEmail();
            $newUser->save();

            Auth::login($newUser);
        }

        // return view('dashboard');
        return response()->json(['message' => 'Logged in successfully.', 'user' => Auth::user()]);
    }
}
