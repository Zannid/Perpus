<?php
namespace App\Http\Controllers;

use App\Models\Notification as NotificationModel;
use App\Models\Peminjaman;
use App\Models\Perpanjangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerpanjanganController extends Controller
{
    /**
     * USER: Ajukan perpanjangan
     */
    public function store(Request $request, $peminjamanId)
    {
        try {
            $peminjaman = Peminjaman::with(['details.buku', 'user'])->findOrFail($peminjamanId);

            Log::info('Store Perpanjangan - Start', [
                'peminjaman_id'      => $peminjamanId,
                'user_id'            => auth()->id(),
                'peminjaman_user_id' => $peminjaman->id_user,
            ]);

            // Validasi: hanya pemilik yang bisa ajukan perpanjangan
            if ($peminjaman->id_user !== auth()->id()) {
                return back()->with('error', 'Anda tidak memiliki akses untuk peminjaman ini!');
            }

            // Validasi: cek apakah bisa diperpanjang
            if (! $peminjaman->canExtend()) {
                return back()->with('error', 'Peminjaman ini tidak bisa diperpanjang. Mungkin sudah ada pengajuan pending atau sudah mencapai batas maksimal perpanjangan.');
            }

            // Validasi input
            $validated = $request->validate([
                'alasan' => 'required|string|max:500',
                'durasi' => 'required|integer|min:1|max:14',
            ]);

            Log::info('Validation passed', $validated);

            // ✅ PERBAIKAN: Convert ke integer dan pastikan tidak null
            $durasi = (int) $validated['durasi'];

            // Hitung tenggat baru
            $tenggat_lama = Carbon::parse($peminjaman->tenggat);
            $tenggat_baru = $tenggat_lama->copy()->addDays($durasi); // ✅ Gunakan integer

            Log::info('Tanggal calculation', [
                'tenggat_lama' => $tenggat_lama->format('Y-m-d'),
                'durasi'       => $durasi,
                'tenggat_baru' => $tenggat_baru->format('Y-m-d'),
            ]);

            // Buat perpanjangan
            $perpanjangan = Perpanjangan::create([
                'id_peminjaman' => $peminjaman->id,
                'tenggat_lama'  => $tenggat_lama->format('Y-m-d'),
                'tenggat_baru'  => $tenggat_baru->format('Y-m-d'),
                'alasan'        => $validated['alasan'],
                'status'        => 'Pending',
            ]);

            Log::info('Perpanjangan created successfully', ['id' => $perpanjangan->id]);

            return back()->with('success', 'Pengajuan perpanjangan berhasil diajukan. Menunggu persetujuan petugas.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Error create perpanjangan', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * PETUGAS: Halaman daftar perpanjangan pending
     */
    public function pending()
    {
        $perpanjangan = Perpanjangan::with(['peminjaman.user', 'peminjaman.details.buku'])
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('perpanjangan.pending', compact('perpanjangan'));
    }

    /**
     * PETUGAS: Approve perpanjangan
     */
    public function approve($id)
    {
        try {
            $perpanjangan = Perpanjangan::with(['peminjaman.user', 'peminjaman.details.buku'])
                ->findOrFail($id);

            Log::info('Approve Perpanjangan - Start', ['id' => $id]);

            // Update perpanjangan
            $perpanjangan->status      = 'Disetujui';
            $perpanjangan->approved_by = auth()->id();
            $perpanjangan->approved_at = now();
            $perpanjangan->save();

            // Update tenggat di peminjaman
            $peminjaman          = $perpanjangan->peminjaman;
            $peminjaman->tenggat = $perpanjangan->tenggat_baru;
            $peminjaman->save();

            Log::info('Perpanjangan approved', [
                'perpanjangan_id' => $perpanjangan->id,
                'peminjaman_id'   => $peminjaman->id,
                'tenggat_baru'    => $peminjaman->tenggat,
            ]);

            // Kirim notifikasi ke user
            try {
                $tenggatFormatted = Carbon::parse($perpanjangan->tenggat_baru)->format('d M Y');

                NotificationModel::create([
                    'user_id' => $peminjaman->id_user,
                    'title'   => 'Perpanjangan Disetujui ✓',
                    'message' => "Perpanjangan peminjaman buku '{$peminjaman->buku->judul}' telah disetujui. Tenggat baru: {$tenggatFormatted}",
                    'type'    => 'success',
                    'link'    => route('peminjaman.show', $peminjaman->id),
                    'is_read' => false,
                ]);

                Log::info('Notification sent to user', ['user_id' => $peminjaman->id_user]);

            } catch (\Exception $e) {
                Log::error('Notif gagal: ' . $e->getMessage());
            }

            return back()->with('success', 'Perpanjangan disetujui dan tenggat telah diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error approve perpanjangan', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * PETUGAS: Reject perpanjangan
     */
    public function reject(Request $request, $id)
    {
        try {
            $perpanjangan = Perpanjangan::with(['peminjaman.user', 'peminjaman.details.buku'])
                ->findOrFail($id);

            Log::info('Reject Perpanjangan - Start', ['id' => $id]);

            $request->validate([
                'alasan_tolak' => 'nullable|string|max:500',
            ]);

            $alasanTolak = $request->alasan_tolak ?? 'Perpanjangan ditolak oleh petugas';

            // Update perpanjangan
            $perpanjangan->status       = 'Ditolak';
            $perpanjangan->alasan_tolak = $alasanTolak;
            $perpanjangan->approved_by  = auth()->id();
            $perpanjangan->approved_at  = now();
            $perpanjangan->save();

            Log::info('Perpanjangan rejected', ['id' => $perpanjangan->id]);

            // Kirim notifikasi ke user
            try {
                NotificationModel::create([
                    'user_id' => $perpanjangan->peminjaman->id_user,
                    'title'   => 'Perpanjangan Ditolak ✗',
                    'message' => "Perpanjangan peminjaman buku '{$perpanjangan->peminjaman->buku->judul}' ditolak. Alasan: {$alasanTolak}",
                    'type'    => 'danger',
                    'link'    => route('peminjaman.show', $perpanjangan->peminjaman->id),
                    'is_read' => false,
                ]);

                Log::info('Notification sent to user');

            } catch (\Exception $e) {
                Log::error('Notif gagal: ' . $e->getMessage());
            }

            return back()->with('success', 'Perpanjangan telah ditolak dan notifikasi dikirim ke user.');

        } catch (\Exception $e) {
            Log::error('Error reject perpanjangan', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * USER: Riwayat perpanjangan
     */
    public function history($peminjamanId)
    {
        try {
            $peminjaman = Peminjaman::with(['perpanjangan', 'details.buku'])->findOrFail($peminjamanId);

            // Validasi: hanya pemilik yang bisa lihat
            if ($peminjaman->id_user !== auth()->id()) {
                abort(403, 'Unauthorized');
            }

            $riwayat = $peminjaman->perpanjangan()
                ->orderBy('created_at', 'desc')
                ->get();

            return view('perpanjangan.history', compact('peminjaman', 'riwayat'));

        } catch (\Exception $e) {
            Log::error('Error history perpanjangan', [
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
