<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Keranjang;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    public function logout(Request $request)
{
    // Hapus session Laravel
    auth()->logout();

    // Hapus session Google (opsional)
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with('status', 'Anda berhasil logout.');
}

    protected function authenticated(Request $request, $user)
    {
        $cart = Keranjang::with('buku')
            ->where('user_id', $user->id)
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->buku_id => [
                        'id_buku'   => $item->buku_id,
                        'judul'     => $item->buku->judul,
                        'kode_buku' => $item->buku->kode_buku,
                        'foto'      => $item->buku->foto,
                        'jumlah'    => $item->jumlah,
                        'stok'      => $item->buku->stok,
                    ]
                ];
            })->toArray();

        session(['cart' => $cart]);
    }


}
