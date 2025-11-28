<?php

namespace App\Http\Controllers;

use App\Models\Notification as NotificationModel; 
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Halaman daftar semua notifikasi
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Tandai sebagai dibaca dan redirect
     */
    public function read($id)
    {
        $notification = NotificationModel::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsRead();

        // Redirect ke link jika ada, atau ke halaman notifikasi
        return redirect($notification->link ?? route('notifications.index'));
    }

    /**
     * Tandai semua sebagai dibaca
     */
    public function markAllRead()
    {
        auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Hapus notifikasi
     */
    public function destroy($id)
    {
        $notification = NotificationModel::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}