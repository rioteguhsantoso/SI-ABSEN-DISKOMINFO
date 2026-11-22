<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        app(NotificationService::class)->generateForUser($user);
        $notifikasi = $user->notifications()
            ->latest()
            ->paginate(15);
        $jumlahBelumDibaca = $user->notifications()
            ->where('dibaca', false)
            ->count();
        return view('notifications.index', compact(
            'notifikasi',
            'jumlahBelumDibaca'
        ));
    }
    public function markAsRead(Request $request, int $id): RedirectResponse
    {
        $notifikasi = $request->user()
            ->notifications()
            ->whereKey($id)
            ->firstOrFail();
        $notifikasi->update([
            'dibaca' => true,
        ]);
        return back();
    }
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()
            ->notifications()
            ->where('dibaca', false)
            ->update([
                'dibaca' => true,
            ]);
        return back();
    }
}
