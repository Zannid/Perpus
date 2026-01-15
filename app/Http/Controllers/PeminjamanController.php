<?php
namespace App\Http\Controllers;

use App\Events\PeminjamanDiajukan;
use App\Mail\PeminjamanRejected;
use App\Mail\PeminjamanReminder;
use App\Models\Buku;
use App\Models\Notification as NotificationModel;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Rating;
use App\Models\User;
use App\Notifications\PeminjamanApprovedNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Midtrans\Config;
use Midtrans\Snap;

class PeminjamanController extends Controller
{
    public function sendReminder($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        Mail::to($peminjaman->user->email)->send(new PeminjamanReminder($peminjaman));

        return back()->with('success', 'Email pengingat berhasil dikirim ke user.');
    }
    public function index(Request $request)
    {
        $tanggalAwal  = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if (auth()->check() && auth()->user()->role === 'user') {
            $query = Peminjaman::with(['buku', 'user'])->where('id_user', auth()->id());
        } else {
            $query = Peminjaman::with(['buku', 'user']);
        }

        if ($tanggalAwal || $tanggalAkhir) {
            if ($tanggalAwal) {
                $startDate = Carbon::parse($tanggalAwal)->startOfDay();
            }

            if ($tanggalAkhir) {
                $endDate = Carbon::parse($tanggalAkhir)->endOfDay();
            }

            if ($tanggalAwal && $tanggalAkhir) {
                $query->whereBetween('tgl_pinjam', [$startDate, $endDate]);
            } elseif ($tanggalAwal) {
                $query->whereDate('tgl_pinjam', '>=', $startDate);
            } elseif ($tanggalAkhir) {
                $query->whereDate('tgl_pinjam', '<=', $endDate);
            }
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            })->orWhereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%$search%");
            })->orWhere('status', 'LIKE', "%$search%");
        }

        $peminjaman = $query->orderByDesc('id')->paginate(5);

        foreach ($peminjaman as $data) {
            $data->formatted_tanggal_pinjam  = Carbon::parse($data->tgl_pinjam)->translatedFormat('l, d F Y');
            $data->formatted_tanggal_kembali = Carbon::parse($data->tenggat)->translatedFormat('l, d F Y');

            // === HITUNG DENDA JIKA SUDAH LEWAT TENGGAT DAN MASIH DIPINJAM ===
            if ($data->status === "Dipinjam") {
                $today = Carbon::today();
                $due   = Carbon::parse($data->tenggat);

                if ($today->gt($due)) {
                    $daysLate    = $due->diffInDays($today);
                    $data->denda = $daysLate * 2000 * $data->jumlah; // contoh Rp 2000 per hari per buku
                } else {
                    $data->denda = 0;
                }
            }
        }

        return view('peminjaman.index', compact('peminjaman', 'tanggalAwal', 'tanggalAkhir', 'request'));
    }

    public function create()
    {
        $buku = Buku::all();
        return view('peminjaman._form', compact('buku'));
    }

    public function markAllRead()
    {
        try {
            Peminjaman::where('status', 'Pending')
                ->where('status_baca', false)
                ->update(['status_baca' => true]);

            return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menandai notifikasi: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $lastId     = Peminjaman::max('id') ?? 0;
        $kodePinjam = 'PJM-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        $request->validate([
            'id_buku'    => 'required|exists:bukus,id',
            'tgl_pinjam' => 'required|date_format:Y-m-d|after_or_equal:today',
            'tenggat'    => 'required|date_format:Y-m-d',
            'jumlah'     => 'required|integer|min:1',
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => $kodePinjam,
            'tgl_pinjam'      => $request->tgl_pinjam,
            'tenggat'         => $request->tenggat,
            'jumlah'          => $request->jumlah,
            'id_user'         => auth()->id(),
            'id_buku'         => $request->id_buku,
            'status'          => "Pending",
        ]);
        // event(new PeminjamanCreated($peminjaman));
        event(new PeminjamanDiajukan($peminjaman));

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dibuat (menunggu persetujuan petugas)');
    }

    public function export(Request $request)
    {
        $tanggalAwal  = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        // Query sama seperti index(), tanpa pagination
        if (auth()->check() && auth()->user()->role === 'user') {
            $query = Peminjaman::with(['buku', 'user'])->where('id_user', auth()->id());
        } else {
            $query = Peminjaman::with(['buku', 'user']);
        }

        if ($tanggalAwal || $tanggalAkhir) {
            if ($tanggalAwal) {
                $startDate = Carbon::parse($tanggalAwal)->startOfDay();
            }

            if ($tanggalAkhir) {
                $endDate = Carbon::parse($tanggalAkhir)->endOfDay();
            }

            if ($tanggalAwal && $tanggalAkhir) {
                $query->whereBetween('tgl_pinjam', [$startDate, $endDate]);
            } elseif ($tanggalAwal) {
                $query->whereDate('tgl_pinjam', '>=', $startDate);
            } elseif ($tanggalAkhir) {
                $query->whereDate('tgl_pinjam', '<=', $endDate);
            }
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('buku', function ($q) use ($search) {
                    $q->where('judul', 'LIKE', "%$search%");
                })->orWhere('status', 'LIKE', "%$search%");
            });
        }

        // Ambil semua data sesuai filter (tanpa pagination)
        $data = $query->orderByDesc('id')->get();

        // Format tanggal agar rapi di PDF
        foreach ($data as $item) {
            $item->formatted_tanggal_pinjam  = Carbon::parse($item->tgl_pinjam)->translatedFormat('l, d F Y');
            $item->formatted_tanggal_kembali = Carbon::parse($item->tenggat)->translatedFormat('l, d F Y');
        }

        $pdf = Pdf::loadView('pdf.peminjaman', ['data' => $data]);

        return $pdf->stream('laporan-data-peminjaman.pdf');

    }

    public function pay($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->denda > 0 && $peminjaman->status !== "Lunas") {
            return view('peminjaman.pay', compact('peminjaman'));
        }

        return back()->with('info', 'Tidak ada denda untuk peminjaman ini.');
    }

    public function confirmPay($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->denda > 0) {
            $peminjaman->update(['status' => "Lunas"]);
            return redirect()->route('peminjaman.index')->with('success', 'Denda berhasil dibayar.');
        }

        return redirect()->route('peminjaman.index')->with('info', 'Tidak ada denda untuk peminjaman ini.');
    }
    public function approve($id)
    {
        Log::info('========== APPROVE START ==========');
        Log::info('Peminjaman ID: ' . $id);

        try {
            $peminjaman = Peminjaman::with(['user', 'details.buku'])->findOrFail($id);

            Log::info('Data Peminjaman:', [
                'id'         => $peminjaman->id,
                'user_id'    => $peminjaman->user_id,
                'user_name'  => optional($peminjaman->user)->name,
                'status'     => $peminjaman->status,
                'total_buku' => $peminjaman->details->count(),
            ]);

            // ================= VALIDASI STOK =================
            foreach ($peminjaman->details as $detail) {
                if (! $detail->buku) {
                    return back()->with('error', 'Data buku tidak ditemukan.');
                }

                if ($detail->buku->stok < $detail->jumlah) {
                    Log::warning('Stok tidak cukup', [
                        'buku' => $detail->buku->judul,
                    ]);
                    return back()->with(
                        'error',
                        'Stok buku "' . $detail->buku->judul . '" tidak mencukupi.'
                    );
                }
            }

            // ================= KURANGI STOK =================
            foreach ($peminjaman->details as $detail) {
                $detail->buku->decrement('stok', $detail->jumlah);
                Log::info('Stok dikurangi', [
                    'buku' => $detail->buku->judul,
                    'sisa' => $detail->buku->fresh()->stok,
                ]);
            }

            // ================= UPDATE PEMINJAMAN =================
            $peminjaman->update([
                'tgl_pinjam'  => now()->toDateString(),
                'tenggat'     => now()->addDays(7)->toDateString(),
                'status'      => 'Dipinjam',
                'status_baca' => true,
            ]);

            Log::info('Status peminjaman updated');

            // ================= NOTIFIKASI BROADCAST =================
            try {
                if ($peminjaman->user) {
                    $peminjaman->user->notify(
                        new PeminjamanApprovedNotification($peminjaman)
                    );
                    Log::info('✓ Broadcast notification sent');
                }
            } catch (\Exception $e) {
                Log::error('Notif broadcast gagal: ' . $e->getMessage());
            }

            // ================= NOTIFIKASI DATABASE =================
            $judulBuku = $peminjaman->details
                ->pluck('buku.judul')
                ->implode(', ');

            $linkUrl = null;
            try {
                $linkUrl = route('peminjaman.show', $peminjaman->id);
            } catch (\Exception $e) {}

            $notif = NotificationModel::create([
                'user_id' => $peminjaman->user_id,
                'title'   => 'Peminjaman Disetujui ✓',
                'message' => "Peminjaman buku ($judulBuku) telah disetujui. Silakan ambil buku di perpustakaan.",
                'type'    => 'success',
                'link'    => $linkUrl,
                'is_read' => false,
            ]);

            Log::info('✓ Notifikasi database dibuat', ['id' => $notif->id]);

            Log::info('========== APPROVE END ==========');

            return back()->with('success', 'Peminjaman disetujui.');

        } catch (\Exception $e) {
            Log::error('========== APPROVE ERROR ==========');
            Log::error($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Tolak peminjaman + Kirim Notifikasi & Email
     * Stok TIDAK DIKURANGI saat Ditolak
     */
    public function reject(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::with(['user', 'buku'])->findOrFail($id);

            // Validasi alasan penolakan
            $request->validate([
                'alasan_tolak' => 'nullable|string|max:500',
            ]);

            // ❌ TIDAK kurangi stok saat ditolak
            // Karena peminjaman belum pernah di-ACC, stok tidak pernah berkurang

            $alasanTolak = $request->alasan_tolak ?? 'Peminjaman ditolak oleh petugas';

            // Update status menjadi Ditolak dan tandai sebagai sudah dibaca
            $peminjaman->status       = "Ditolak";
            $peminjaman->status_baca  = true;
            $peminjaman->alasan_tolak = $alasanTolak;
            $peminjaman->save();

            // Kirim notifikasi ke tabel notifications (untuk bell icon)
            try {
                Notification::create([
                    'user_id' => $peminjaman->user_id,
                    'title'   => 'Peminjaman Ditolak ✗',
                    'message' => "Pengajuan peminjaman buku '{$peminjaman->buku->judul}' ditolak. Alasan: {$alasanTolak}",
                    'type' => 'danger',
                    'link' => route('peminjaman.show', $peminjaman->id),
                ]);
            } catch (\Exception $e) {
                Log::error('Notif database gagal: ' . $e->getMessage());
            }

            // Kirim email notifikasi (cek email valid dulu)
            if ($peminjaman->user && $peminjaman->user->email && filter_var($peminjaman->user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($peminjaman->user->email)->send(new PeminjamanRejected($peminjaman));
                    return back()->with('success', 'Peminjaman berhasil ditolak (stok tidak berkurang), notifikasi dan email telah dikirim');
                } catch (\Exception $e) {
                    Log::error('Email gagal dikirim ke ' . $peminjaman->user->email . ': ' . $e->getMessage());
                    return back()->with('warning', 'Peminjaman berhasil ditolak (stok tidak berkurang) dan notifikasi terkirim, tapi email gagal dikirim: ' . $e->getMessage());
                }
            } else {
                Log::warning('Email user tidak valid untuk peminjaman ID: ' . $peminjaman->id);
                return back()->with('warning', 'Peminjaman berhasil ditolak (stok tidak berkurang) dan notifikasi terkirim, tapi user tidak memiliki email yang valid');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function Pending(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku'])
            ->where('status', 'Pending');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('buku', function ($q3) use ($search) {
                        $q3->where('judul', 'like', "%{$search}%");
                    });
            });
        }

        $peminjaman = $query->latest()->paginate(5);

        // Tandai semua notifikasi di halaman ini sebagai sudah dibaca
        Peminjaman::where('status', 'Pending')
            ->where('status_baca', false)
            ->update(['status_baca' => true]);

        return view('peminjaman.acc', compact('peminjaman'));
    }

    public function return (Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $buku       = Buku::findOrFail($peminjaman->id_buku);

        $today    = Carbon::now();
        $denda    = 0;
        $daysLate = 0;

        // hitung keterlambatan
        if ($today->greaterThan($peminjaman->tenggat)) {
            $daysLate = $today->diffInDays($peminjaman->tenggat);
        }

        // tentukan denda per buku
        if ($request->kondisi == "Bagus") {
            if ($daysLate > 0) {
                $denda = 10000 + ($daysLate * 2000);
            }
        } elseif ($request->kondisi == "Rusak") {
            $denda = 20000;
            if ($daysLate > 0) {
                $denda += ($daysLate * 2000);
            }
        } elseif ($request->kondisi == "Hilang") {
            $denda = 50000;
        }

        // total denda = denda per buku * jumlah buku yang dipinjam
        $denda  = $denda * $peminjaman->jumlah;

        // jika tidak hilang, kembalikan stok
        if ($request->kondisi != "Hilang") {
            $buku->stok += $peminjaman->jumlah;
            $buku->save();
        }

        // simpan ke tabel pengembalian
        pengembalian::create([
            'id_peminjaman' => $peminjaman->id,
            'id_user'       => $peminjaman->id_user,
            'id_buku'       => $peminjaman->id_buku,
            'tgl_pinjam'    => $peminjaman->tgl_pinjam,
            'tenggat'       => $peminjaman->tenggat,
            'tgl_kembali'   => $today,
            'jumlah'        => $peminjaman->jumlah,
            'kondisi'       => $request->kondisi,
            'denda'         => $denda,
        ]);

        // update status peminjaman
        $peminjaman->status  = "Kembali";
        $peminjaman->denda   = $denda;
        $peminjaman->kondisi = $request->kondisi;
        $peminjaman->save();

        // Redirect ke halaman rating
        $message = $denda > 0
            ? "Buku dikembalikan dengan kondisi {$request->kondisi}. Denda: Rp " . number_format($denda, 0, ',', '.') . ". Silakan berikan rating."
            : "Buku dikembalikan dengan kondisi {$request->kondisi} tanpa denda. Silakan berikan rating.";

        $type = $denda > 0 ? 'warning' : 'success';

        return redirect()->route('rating.show', $peminjaman->id)
            ->with($type, $message);
    }
    public function destroy($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status === "Dipinjam") {
                $buku = Buku::findOrFail($peminjaman->id_buku);
                $buku->increment('stok', $peminjaman->jumlah);

                $peminjaman->delete();
                return redirect()->route('peminjaman.index')
                    ->with('success', 'Peminjaman berhasil dihapus dan stok dikembalikan (' . $peminjaman->jumlah . ' buku)');
            }

            $peminjaman->delete();
            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dihapus (stok tidak berubah karena status: ' . $peminjaman->status . ')');

        } catch (\Exception $e) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Gagal menghapus peminjaman: ' . $e->getMessage());
        }
    }

    public function readNotif($id)
    {
        try {
            $peminjaman = Peminjaman::find($id);

            if (! $peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan',
                ], 404);
            }

            if (! $peminjaman->status_baca) {
                $peminjaman->status_baca = true;
                $peminjaman->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil ditandai sebagai dibaca',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function pengembalianIndex(Request $request)
    {
        $query = \App\Models\Pengembalian::with(['peminjaman', 'user', 'buku']);

        // Filter user login
        if (auth()->check() && auth()->user()->role === 'user') {
            $query->where('id_user', auth()->id());
        }

        // Search jika ada
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%$search%"))
                    ->orWhereHas('buku', fn($bq) => $bq->where('judul', 'like', "%$search%"))
                    ->orWhere('kondisi', 'like', "%$search%");
            });
        }

        $pengembalian = $query->latest()->paginate(10);

        return view('pengembalian.index', compact('pengembalian'));
    }

    public function payQris($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Setup Midtrans Config
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id'     => 'PMJ-' . $peminjaman->id . '-' . time(),
                'gross_amount' => $peminjaman->denda,
            ],
            'customer_details'    => [
                'first_name' => $peminjaman->user->name,
                'email'      => $peminjaman->user->email,
            ],
            'enabled_payments'    => ['qris'], // khusus QRIS
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('peminjaman.qris', compact('peminjaman', 'snapToken'));
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $buku       = Buku::all();
        return view('peminjaman._form', compact('peminjaman', 'buku'));
    }
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $request->validate([
            'id_buku'    => 'required|exists:bukus,id',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'jumlah'     => 'required|integer|min:1',
        ]);
        $tglPinjam = Carbon::parse($request->tgl_pinjam);
        $tenggat   = $tglPinjam->copy()->addDays(7);
        $peminjaman->update([
            'id_buku'    => $request->id_buku,
            'tgl_pinjam' => $tglPinjam,
            'tenggat'    => $tenggat,
            'jumlah'     => $request->jumlah,
        ]);
        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diperbarui');
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'id_buku' => 'required|exists:bukus,id',
            'jumlah'  => 'required|integer|min:1',
        ]);

        $buku = Buku::find($request->id_buku);

        if ($buku->stok < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok buku tidak mencukupi.',
            ]);
        }

        // Simpan peminjaman baru (status Pending)
        $peminjaman             = new Peminjaman();
        $peminjaman->id_buku    = $buku->id;
        $peminjaman->id_user    = auth()->id();
        $peminjaman->jumlah     = $request->jumlah;
        $peminjaman->tgl_pinjam = null;
        $peminjaman->tenggat    = null;
        $peminjaman->status     = 'Pending';
        $peminjaman->save();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan peminjaman berhasil dikirim.',
        ]);
    }
    public function storeAuto(Request $request)
    {
        try {
            if (! auth()->check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $buku = Buku::findOrFail($request->id);

            if ($buku->stok <= 0) {
                return back()->with('error', 'Stok buku habis.');
            }

            $lastId = Peminjaman::max('id') ?? 0;
            $kode   = 'PJM-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            Peminjaman::create([
                'kode_peminjaman' => $kode,
                'id_user'         => auth()->id(),
                'id_buku'         => $buku->id,
                'jumlah'          => 1,
                'tgl_pinjam'      => null,
                'tenggat'         => null,
                'status'          => 'Pending',
            ]);

            $buku->decrement('stok');

            return back()->with('success', 'Peminjaman berhasil diajukan dengan kode ' . $kode);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    public function showRating($id)
    {
        $peminjaman = Peminjaman::with(['buku', 'user'])->findOrFail($id);

        // Cek apakah sudah diberi rating
        if ($peminjaman->rating) {
            return redirect()->route('peminjaman.index')
                ->with('info', 'Peminjaman ini sudah diberi rating.');
        }

        return view('peminjaman.rating', compact('peminjaman'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'buku'])->findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    }

}
