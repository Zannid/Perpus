<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request; // Sudah diperbaiki (I kapital)
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'data'    => $users,
        ]);

    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|max:255|unique:users',
            'password'  => 'required|string|min:8',
            'no_telpon' => 'nullable|string|max:20',
            'alamat'    => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            // Gunakan 422 untuk error validasi input
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'user',
            'no_telpon' => $request->no_telpon,
            'alamat'    => $request->alamat,
            'kode_user' => $this->generateKodeUser(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'data'         => $user,
            'access_token' => $token, // Tambahkan token saat register agar user langsung login
            'message'      => 'Register Success',
        ], 201);
    }

    /**
     * Generate unique user code
     */
    private function generateKodeUser()
    {
        $prefix    = 'USR';
        $timestamp = time();
        $randomNum = rand(100, 999);

        $kodeUser = $prefix . '-' . $timestamp . '-' . $randomNum;

        // Ensure uniqueness
        while (User::where('kode_user', $kodeUser)->exists()) {
            $randomNum = rand(100, 999);
            $kodeUser  = $prefix . '-' . $timestamp . '-' . $randomNum;
        }

        return $kodeUser;
    }

public function login(Request $request)
{
    
    $credentials = $request->only('email', 'password');
    if (!Auth::attempt($credentials)) {
        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'token' => $token,
        'user' => $user
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout Success',
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => $user,
        ]);
    }

    // ... fungsi login & logout sudah oke
}
