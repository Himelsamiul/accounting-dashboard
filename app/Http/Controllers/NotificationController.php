<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AdminNotification::latest()->paginate(30);

        return view('notifications.index', compact('notifications'));
    }

    /** Mark one as read, then jump to its linked page. */
    public function open(AdminNotification $notification)
    {
        $notification->update(['is_read' => true]);

        return redirect($notification->url ?: route('notifications.index'));
    }

    public function readAll()
    {
        AdminNotification::unread()->update(['is_read' => true]);

        return back()->with('status', 'All notifications marked as read.');
    }

    /** Lightweight JSON feed polled by the admin layout to show live popups. */
    public function feed()
    {
        $items = AdminNotification::latest()->take(8)->get()->map(fn ($n) => [
            'id' => $n->id,
            'title' => $n->title,
            'body' => $n->body,
            'icon' => $n->icon ?: $n->type,
            'url' => route('notifications.open', $n->id),
            'time' => $n->created_at->diffForHumans(),
            'is_read' => (bool) $n->is_read,
        ]);

        return response()->json([
            'unread' => AdminNotification::unread()->count(),
            'items' => $items,
        ]);
    }
}
