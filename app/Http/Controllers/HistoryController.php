<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'user_id' => $request->input('user_id', ''),
            'action' => $request->input('action', ''),
            'from' => $request->input('from', ''),
            'to' => $request->input('to', ''),
            'q' => trim((string) $request->input('q', '')),
        ];

        $logs = ActivityLog::with('user')
            ->when($filters['user_id'] !== '', fn ($q) => $q->where('user_id', $filters['user_id']))
            ->when($filters['action'] !== '', fn ($q) => $q->where('action', $filters['action']))
            ->when($filters['from'] !== '', fn ($q) => $q->whereDate('created_at', '>=', $filters['from']))
            ->when($filters['to'] !== '', fn ($q) => $q->whereDate('created_at', '<=', $filters['to']))
            ->when($filters['q'] !== '', function ($q) use ($filters) {
                $term = '%' . $filters['q'] . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('subject_label', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('user_name', 'like', $term)
                        ->orWhere('subject_type', 'like', $term);
                });
            })
            ->latest()
            ->paginate(40)
            ->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name']);
        $actions = ActivityLog::distinctActions();

        return view('history.index', compact('logs', 'users', 'actions', 'filters'));
    }

    /** Clear activity logs (optionally only up to a date). */
    public function clear(Request $request)
    {
        $before = $request->input('before');

        $query = ActivityLog::query();
        if ($before) {
            $query->whereDate('created_at', '<=', $before);
        }
        $deleted = $query->delete();

        return back()->with('status', "Cleared {$deleted} history record(s).");
    }
}
