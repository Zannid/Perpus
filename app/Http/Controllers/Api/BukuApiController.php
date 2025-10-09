<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Buku::all());
        return response()->json($books)
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $buku = Buku::create($request->all());
        return response()->json($buku, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::find($id);
        if ($buku) {
            return response()->json($buku);
        } else {
            return response()->json(['message' => 'Buku not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);
        if ($buku) {
            $buku->update($request->all());
            return response()->json($buku);
        } else {
            return response()->json(['message' => 'Buku not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::find($id);
        if ($buku) {
            $buku->delete();
            return response()->json(['message' => 'Buku deleted']);
        } else {
            return response()->json(['message' => 'Buku not found'], 404);
        }
    }
}
