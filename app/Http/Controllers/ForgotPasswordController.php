<?php
namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = rand(100000, 999999); // kode 6 digit

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // Kirim email
        Mail::raw("Kode reset password kamu adalah: $token", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Kode Reset Password');
        });

        return redirect()->route('password.verify')
            ->with('email', $request->email)
            ->with('success', 'Kode telah dikirim ke email kamu!');
    }

    public function showVerifyForm(Request $request)
    {
        $email = session('email');
        return view('auth.verify-code', compact('email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (! $record) {
            return back()->with('error', 'Kode tidak valid atau sudah digunakan.');
        }

        // Ubah password
        $user           = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Hapus kode reset setelah digunakan
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset!');
    }
}
