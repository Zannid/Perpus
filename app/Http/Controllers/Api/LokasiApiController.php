<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lokasi;

class LokasiApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Lokasi::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lokasi = Lokasi::create($request->all());
        return response()->json($lokasi, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lokasi = Lokasi::find($id);
        if ($lokasi) {
            return response()->json($lokasi);
        } else{
            return response()->json(['message' => 'lokasi not found'], 404);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lokasi = Lokasi::find($id);
        if ($lokasi) {
            $lokasi->update($request->all());
            return response()->json($lokasi);
        } else {
            return response()->json(['message' => 'lokasi not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lokasi = Lokasi::find($id);
        if ($lokasi) {
            $lokasi->delete();
            return response()->json(['message' => 'lokasi deleted']);
        } else {
            return response()->json(['message' => 'lokasi not found'], 404);
        }
    }
}
