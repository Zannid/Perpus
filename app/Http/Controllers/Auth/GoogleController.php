<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(str()->random(16)), // password random
                    'email_verified_at' => now()
                ]
            );

            Auth::login($user);

            return redirect()->route('dashboard'); // arahkan ke dashboard
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login gagal, coba lagi.');
        }
    }
}
