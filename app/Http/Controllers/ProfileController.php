<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'no_telpon' => 'nullable|string|max:15',
            'alamat'    => 'nullable|string|max:500',
        ]);

       if ($request->hasFile('foto')) {

    $folder = in_array($user->role, ['admin', 'petugas', 'user'])
    ? $user->role
    : 'user';

    if ($user->foto && Storage::disk('public')->exists($folder.'/'.$user->foto)) {
        Storage::disk('public')->delete($folder.'/'.$user->foto);
    }

    $file = $request->file('foto');
    $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

    $file->storeAs($folder, $filename, 'public');

    $user->foto = $filename;
}

        $user->name      = $request->name;
        $user->email     = $request->email;
        $user->no_telpon = $request->no_telpon;
        $user->alamat    = $request->alamat;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile berhasil diperbarui!');
    }
}
