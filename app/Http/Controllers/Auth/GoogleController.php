<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan google_id ATAU email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update data Google kalau belum ada
                if (! $user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'provider'  => 'google',
                    ]);
                }
            } else {
                // Buat user baru
                $user = User::create([
                    'name'              => $googleUser->getName() ?? $googleUser->getNickname(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'provider'          => 'google',
                    'password'          => bcrypt(Str::random(32)),
                    'email_verified_at' => now(),
                    'role'              => 'user', // default role
                ]);

                // Download foto dari Google jika ada
                if ($googleUser->getAvatar()) {
                    $avatarUrl      = $googleUser->getAvatar();
                    $avatarContents = file_get_contents($avatarUrl);
                    if ($avatarContents) {
                        $filename = 'google_' . $user->id . '.jpg';
                        Storage::put('public/user/' . $filename, $avatarContents);
                        $user->update(['foto' => $filename]);
                    }
                }
            }

            Auth::login($user, true); // remember login

            return redirect()->intended(route('dashboard'));

        } catch (\Throwable $e) {
            // 🔥 UNCOMMENT kalau mau lihat error asli
            // dd($e->getMessage());

            return redirect()
                ->route('login')
                ->with('error', 'Login Google gagal, silakan coba lagi.');
        }
    }
}
