<?php
namespace App\Http\Controllers;

use App\Mail\PeminjamanRejected;
use App\Mail\PeminjamanReminder;
use App\Models\Buku;
use App\Models\Notification as NotificationModel;
use App\Models\Peminjaman;
use App\Models\Rating;
use App\Models\User;
use App\Notifications\PeminjamanApprovedNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            $query = Peminjaman::with(['details.buku', 'user', 'rating'])->where('id_user', auth()->id());
        } else {
            $query = Peminjaman::with(['details.buku', 'user', 'rating']);
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
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'LIKE', "%$search%");
                })->orWhereHas('details.buku', function ($q2) use ($search) {
                    $q2->where('judul', 'LIKE', "%$search%");
                })->orWhere('status', 'LIKE', "%$search%")
                    ->orWhere('kode_peminjaman', 'LIKE', "%$search%");
            });
        }

        $peminjaman = $query->orderByDesc('id')->paginate(5);

        foreach ($peminjaman as $data) {
            $data->formatted_tanggal_pinjam  = Carbon::parse($data->tgl_pinjam)->translatedFormat('l, d F Y');
            $data->formatted_tanggal_kembali = Carbon::parse($data->tenggat)->translatedFormat('l, d F Y');

            // Hitung denda jika sudah lewat tenggat dan masih dipinjam
            if ($data->status === "Dipinjam") {
                $today = Carbon::today();
                $due   = Carbon::parse($data->tenggat);

                if ($today->gt($due)) {
                    $daysLate    = $due->diffInDays($today);
                    $totalJumlah = $data->details->sum('jumlah') ?: $data->jumlah_keseluruhan ?: 1;
                    $data->denda = $daysLate * 2000 * $totalJumlah;
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
        $user = User::all();
        return view('peminjaman._form', compact('buku', 'user'));
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
        $request->validate([
            'id_user'    => 'required|exists:users,id',
            'id_buku'    => 'required|array',
            'id_buku.*'  => 'exists:bukus,id',
            'jumlah'     => 'required|array',
            'jumlah.*'   => 'integer|min:1',
            'tgl_pinjam' => 'required|date',
            'tenggat'    => 'required|date|after_or_equal:tgl_pinjam',
            'status'     => 'required|in:Pending,Dipinjam',
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman'    => 'PMJ-' . (Peminjaman::max('id') + 1),
            'id_user'            => $request->id_user,
            'tgl_pinjam'         => $request->tgl_pinjam,
            'tenggat'            => $request->tenggat,
            'status'             => $request->status,
            'jumlah_keseluruhan' => array_sum($request->jumlah),
        ]);

        foreach ($request->id_buku as $index => $bukuId) {
            \App\Models\DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'buku_id'       => $bukuId,
                'jumlah'        => $request->jumlah[$index],
            ]);

            // PERBAIKAN: Stok hanya dikurangi jika status langsung Dipinjam
            if ($request->status == 'Dipinjam') {
                Buku::find($bukuId)->decrement('stok', $request->jumlah[$index]);
            }
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dibuat');
    }

    public function export(Request $request)
    {
        $tanggalAwal  = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if (auth()->check() && auth()->user()->role === 'user') {
            $query = Peminjaman::with(['details.buku', 'user'])->where('id_user', auth()->id());
        } else {
            $query = Peminjaman::with(['details.buku', 'user']);
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
                })->orWhereHas('details.buku', function ($q) use ($search) {
                    $q->where('judul', 'LIKE', "%$search%");
                })->orWhere('status', 'LIKE', "%$search%");
            });
        }

        $data = $query->orderByDesc('id')->get();

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

    public function approve(Request $request, $id)
    {
        Log::info('========== APPROVE START ==========');
        Log::info('Peminjaman ID: ' . $id);

        try {
            $peminjaman = Peminjaman::with(['user', 'details.buku'])->findOrFail($id);

            Log::info('Data Peminjaman:', [
                'id'         => $peminjaman->id,
                'user_id'    => $peminjaman->id_user,
                'user_name'  => optional($peminjaman->user)->name,
                'status'     => $peminjaman->status,
                'total_buku' => $peminjaman->details->count(),
            ]);

            // PERBAIKAN: Validasi hanya untuk status Pending
            if ($peminjaman->status !== 'Pending') {
                return back()->with('error', 'Hanya peminjaman dengan status Pending yang dapat disetujui.');
            }

            // Validasi stok
            foreach ($peminjaman->details as $detail) {
                if (!$detail->buku) {
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

            // Kurangi stok (karena sebelumnya tidak dikurangi saat Pending)
            foreach ($peminjaman->details as $detail) {
                $detail->buku->decrement('stok', $detail->jumlah);
                Log::info('Stok dikurangi', [
                    'buku' => $detail->buku->judul,
                    'sisa' => $detail->buku->fresh()->stok,
                ]);
            }

            // Update peminjaman
            $tglPinjam = $request->tgl_pinjam ?: now()->toDateString();
            $tenggat   = $request->tenggat ?: now()->addDays(7)->toDateString();

            $peminjaman->update([
                'tgl_pinjam'  => $tglPinjam,
                'tenggat'     => $tenggat,
                'status'      => 'Dipinjam',
                'status_baca' => true,
            ]);

            Log::info('Status peminjaman updated');

            // Notifikasi broadcast
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

            // Notifikasi database
            $judulBuku = $peminjaman->details
                ->pluck('buku.judul')
                ->implode(', ');

            $linkUrl = null;
            try {
                $linkUrl = route('peminjaman.show', $peminjaman->id);
            } catch (\Exception $e) {}

            $notif = NotificationModel::create([
                'user_id' => $peminjaman->id_user,
                'title'   => 'Peminjaman Disetujui ✓',
                'message' => "Peminjaman buku ($judulBuku) telah disetujui. Silakan ambil buku di perpustakaan.",
                'type'    => 'success',
                'link'    => $linkUrl,
                'is_read' => false,
            ]);

            Log::info('✓ Notifikasi database dibuat', ['id' => $notif->id]);

            // Kirim email approval
            if ($peminjaman->user && $peminjaman->user->email && filter_var($peminjaman->user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($peminjaman->user->email)->send(new \App\Mail\PeminjamanApproved($peminjaman));
                    Log::info('✓ Email approval sent');
                } catch (\Exception $e) {
                    Log::error('Email approval gagal: ' . $e->getMessage());
                }
            }

            Log::info('========== APPROVE END ==========');

            return back()->with('success', 'Peminjaman disetujui.');

        } catch (\Exception $e) {
            Log::error('========== APPROVE ERROR ==========');
            Log::error($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::with(['user', 'details.buku'])->findOrFail($id);

            // Validasi alasan penolakan
            $request->validate([
                'alasan_tolak' => 'nullable|string|max:500',
            ]);

            // PERBAIKAN: Cek status sebelum reject
            if ($peminjaman->status !== 'Pending') {
                return back()->with('error', 'Hanya peminjaman dengan status Pending yang dapat ditolak.');
            }

            // PERBAIKAN: Stok TIDAK perlu dikembalikan karena belum pernah dikurangi
            // Saat status Pending, stok belum dikurangi, jadi tidak perlu increment

            $alasanTolak = $request->alasan_tolak ?? 'Peminjaman ditolak oleh petugas';

            // Update status menjadi Ditolak
            $peminjaman->status       = "Ditolak";
            $peminjaman->status_baca  = true;
            $peminjaman->alasan_tolak = $alasanTolak;
            $peminjaman->save();

            // Kirim notifikasi ke tabel notifications
            try {
                $judulBuku = $peminjaman->details->pluck('buku.judul')->implode(', ');
                \App\Models\Notification::create([
                    'user_id' => $peminjaman->id_user,
                    'title'   => 'Peminjaman Ditolak ✗',
                    'message' => "Pengajuan peminjaman buku '{$judulBuku}' ditolak. Alasan: {$alasanTolak}",
                    'type'    => 'danger',
                    'link'    => route('peminjaman.show', $peminjaman->id),
                ]);
            } catch (\Exception $e) {
                Log::error('Notif database gagal: ' . $e->getMessage());
            }

            // Kirim email notifikasi
            if ($peminjaman->user && $peminjaman->user->email && filter_var($peminjaman->user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($peminjaman->user->email)->send(new PeminjamanRejected($peminjaman));
                    return back()->with('success', 'Peminjaman berhasil ditolak, notifikasi dan email telah dikirim');
                } catch (\Exception $e) {
                    Log::error('Email gagal dikirim ke ' . $peminjaman->user->email . ': ' . $e->getMessage());
                    return back()->with('warning', 'Peminjaman berhasil ditolak dan notifikasi terkirim, tapi email gagal dikirim: ' . $e->getMessage());
                }
            } else {
                Log::warning('Email user tidak valid untuk peminjaman ID: ' . $peminjaman->id);
                return back()->with('warning', 'Peminjaman berhasil ditolak dan notifikasi terkirim, tapi user tidak memiliki email yang valid');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function Pending(Request $request)
    {
        $query = Peminjaman::with(['user', 'details.buku'])
            ->where('status', 'Pending');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('details.buku', function ($q3) use ($search) {
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

    public function return(Request $request, $id)
    {
        $peminjaman  = Peminjaman::with('details.buku')->findOrFail($id);
        $firstDetail = $peminjaman->details->first();
        $buku        = $firstDetail ? $firstDetail->buku : null;

        if (!$buku && $request->kondisi != "Hilang") {
            return back()->with('error', 'Data buku tidak ditemukan.');
        }

        $today    = Carbon::now();
        $denda    = 0;
        $daysLate = 0;

        // Hitung keterlambatan
        if ($today->greaterThan($peminjaman->tenggat)) {
            $daysLate = $today->diffInDays($peminjaman->tenggat);
        }

        // Tentukan denda per buku
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

        $dendaPerBuku          = $denda;
        $totalDendaKeseluruhan = 0;

        // Loop semua detail buku untuk membuat record pengembalian terpisah
        foreach ($peminjaman->details as $detail) {
            if (!$detail->buku && $request->kondisi != "Hilang") {
                return back()->with('error', 'Data buku tidak ditemukan untuk salah satu detail peminjaman.');
            }

            // Jika tidak hilang, kembalikan stok
            if ($request->kondisi != "Hilang" && $detail->buku) {
                $detail->buku->increment('stok', $detail->jumlah);
            }

            $dendaUntukDetail       = $dendaPerBuku * $detail->jumlah;
            $totalDendaKeseluruhan += $dendaUntukDetail;

            // Simpan ke tabel pengembalian
            \App\Models\Pengembalian::create([
                'id_peminjaman' => $peminjaman->id,
                'id_user'       => $peminjaman->id_user,
                'id_buku'       => $detail->buku_id,
                'tgl_pinjam'    => $peminjaman->tgl_pinjam,
                'tenggat'       => $peminjaman->tenggat,
                'tgl_kembali'   => $today->toDateString(),
                'jumlah'        => $detail->jumlah,
                'denda'         => $dendaUntukDetail,
                'kondisi'       => $request->kondisi,
            ]);
        }

        // Update status peminjaman
        $peminjaman->update([
            'status' => 'Kembali',
            'denda'  => $totalDendaKeseluruhan,
        ]);

        $message = $totalDendaKeseluruhan > 0
            ? "Buku dikembalikan dengan kondisi {$request->kondisi}. Total Denda: Rp " . number_format($totalDendaKeseluruhan, 0, ',', '.') . ". Silakan berikan rating."
            : "Buku dikembalikan dengan kondisi {$request->kondisi} tanpa denda. Silakan berikan rating.";

        $type = $totalDendaKeseluruhan > 0 ? 'warning' : 'success';

        return redirect()->route('rating.show', $peminjaman->id)
            ->with($type, $message);
    }

    public function destroy($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // PERBAIKAN: Kembalikan stok hanya jika status Dipinjam
            if ($peminjaman->status === "Dipinjam") {
                foreach ($peminjaman->details as $detail) {
                    if ($detail->buku) {
                        $detail->buku->increment('stok', $detail->jumlah);
                    }
                }

                $peminjaman->delete();
                return redirect()->route('peminjaman.index')
                    ->with('success', 'Peminjaman berhasil dihapus dan stok dikembalikan');
            }

            // Untuk status Pending, Ditolak, Kembali - tidak perlu kembalikan stok
            $peminjaman->delete();
            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Gagal menghapus peminjaman: ' . $e->getMessage());
        }
    }

    public function readNotif($id)
    {
        try {
            $peminjaman = Peminjaman::find($id);

            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan',
                ], 404);
            }

            if (!$peminjaman->status_baca) {
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
            'enabled_payments'    => ['qris'],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('peminjaman.qris', compact('peminjaman', 'snapToken'));
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::with('details.buku')->findOrFail($id);
        $buku       = Buku::all();
        $user       = User::all();
        return view('peminjaman._form', compact('peminjaman', 'buku', 'user'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $request->validate([
            'id_user'    => 'required|exists:users,id',
            'id_buku'    => 'required|array',
            'id_buku.*'  => 'exists:bukus,id',
            'jumlah'     => 'required|array',
            'jumlah.*'   => 'integer|min:1',
            'tgl_pinjam' => 'required|date',
            'tenggat'    => 'required|date|after_or_equal:tgl_pinjam',
            'status'     => 'required|in:Pending,Dipinjam,Kembali,Ditolak',
        ]);

        $statusLama = $peminjaman->status;
        $statusBaru = $request->status;

        // PERBAIKAN: Kembalikan stok hanya jika dari Dipinjam ke status lain
        if ($statusLama === 'Dipinjam' && $statusBaru !== 'Dipinjam') {
            foreach ($peminjaman->details as $detail) {
                if ($detail->buku) {
                    $detail->buku->increment('stok', $detail->jumlah);
                }
            }
        }

        $peminjaman->update([
            'id_user'            => $request->id_user,
            'tgl_pinjam'         => $request->tgl_pinjam,
            'tenggat'            => $request->tenggat,
            'status'             => $statusBaru,
            'jumlah_keseluruhan' => array_sum($request->jumlah),
        ]);

        // Sync details
        $peminjaman->details()->delete();
        foreach ($request->id_buku as $index => $bukuId) {
            \App\Models\DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'buku_id'       => $bukuId,
                'jumlah'        => $request->jumlah[$index],
            ]);
        }

        // PERBAIKAN: Kurangi stok hanya jika dari status lain ke Dipinjam
        if ($statusLama !== 'Dipinjam' && $statusBaru === 'Dipinjam') {
            foreach ($request->id_buku as $index => $bukuId) {
                Buku::find($bukuId)->decrement('stok', $request->jumlah[$index]);
            }
        }

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

        // PERBAIKAN: Stok tidak dikurangi saat Pending
        $peminjaman                     = new Peminjaman();
        $peminjaman->id_user            = auth()->id();
        $peminjaman->jumlah_keseluruhan = $request->jumlah;
        $peminjaman->tgl_pinjam         = null;
        $peminjaman->tenggat            = null;
        $peminjaman->status             = 'Pending';

        $lastId                      = Peminjaman::max('id') ?? 0;
        $peminjaman->kode_peminjaman = 'PJM-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        $peminjaman->save();

        // Simpan detail
        \App\Models\DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'buku_id'       => $buku->id,
            'jumlah'        => $request->jumlah,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan peminjaman berhasil dikirim.',
        ]);
    }

    public function storeAuto(Request $request)
    {
        try {
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $buku = Buku::findOrFail($request->id);

            if ($buku->stok <= 0) {
                return back()->with('error', 'Stok buku habis.');
            }

            $lastId = Peminjaman::max('id') ?? 0;
            $kode   = 'PJM-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            // PERBAIKAN: Stok tidak dikurangi saat Pending
            $peminjaman = Peminjaman::create([
                'kode_peminjaman'    => $kode,
                'id_user'            => auth()->id(),
                'jumlah_keseluruhan' => 1,
                'tgl_pinjam'         => null,
                'tenggat'            => null,
                'status'             => 'Pending',
            ]);

            \App\Models\DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'buku_id'       => $buku->id,
                'jumlah'        => 1,
            ]);

            return back()->with('success', 'Peminjaman berhasil diajukan dengan kode ' . $kode);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    public function showRating($id)
    {
        $peminjaman = Peminjaman::with(['details.buku', 'user'])->findOrFail($id);

        // Cek apakah sudah diberi rating
        if ($peminjaman->rating) {
            return redirect()->route('peminjaman.index')
                ->with('info', 'Peminjaman ini sudah diberi rating.');
        }

        return view('peminjaman.rating', compact('peminjaman'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'details.buku'])->findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    }
}