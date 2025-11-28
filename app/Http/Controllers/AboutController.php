<?php
namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $about = About::orderByDesc('created_at')->get();
        return view('about.index', compact('about'));
    }

    /**
     * Toggle / update status aktif untuk 1 about.
     * Jika request mengirim is_active = 1 -> set semua lain false, set this true.
     * Jika request mengirim is_active = 0 -> set this false (tidak ada yang aktif).
     */
    public function updateStatus(Request $request, $id)
    {
        // Ambil nilai yang dikirim (bisa '1' atau '0' atau tidak ada)
        // Pastikan kita membaca sebagai boolean
        $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        $about = About::findOrFail($id);

        if ($isActive) {
            // Non-aktifkan semua terlebih dahulu, lalu aktifkan yang dipilih
            About::query()->update(['is_active' => false]);

            $about->is_active = true;
            $about->save();

            return redirect()->back()->with('success', 'About dipilih sebagai aktif.');
        } else {
            // Jika checkbox menjadi unchecked -> set this false
            $about->is_active = false;
            $about->save();

            return redirect()->back()->with('success', 'About dinonaktifkan.');
        }
    }

    public function create()
    {
                                     // Create form tidak perlu mengambil about aktif
        return view('about._form'); // sesuaikan nama view form create
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        // Normalisasi is_active (karena checkbox bisa tidak dikirim)
        $isActive                   = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        $validatedData['is_active'] = $isActive;

        // Jika user membuat About baru dan memilih is_active = true,
        // nonaktifkan About lain supaya hanya 1 yang aktif
        if ($isActive) {
            About::query()->update(['is_active' => false]);
        }

        About::create($validatedData);

        return redirect()->route('about.index')->with('success', 'About section created successfully.');
    }

    public function show($id)
    {
        $about = About::findOrFail($id);
        return view('about.show', compact('about'));
    }

    public function edit($id)
    {
        $about = About::findOrFail($id);
        return view('about._form', compact('about'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $about = About::findOrFail($id);

        $isActive                   = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        $validatedData['is_active'] = $isActive;

        if ($isActive) {
            About::query()->update(['is_active' => false]);
        }

        $about->update($validatedData);

        return redirect()->route('about.index')->with('success', 'About section updated successfully.');
    }

    public function destroy($id)
    {
        $about = About::findOrFail($id);
        $about->delete();

        return redirect()->route('about.index')->with('success', 'About section deleted successfully.');
    }
}
