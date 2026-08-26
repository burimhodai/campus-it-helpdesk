<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $counts = [
            'open' => Ticket::where('status', 'open')->count(),
            'active' => Ticket::whereIn('status', ['in_progress', 'waiting_user'])->count(),
            'urgent' => Ticket::where('priority', 'urgent')->whereNotIn('status', ['resolved', 'closed'])->count(),
            'unassigned' => Ticket::whereNull('assigned_to')->whereNotIn('status', ['resolved', 'closed'])->count(),
            'users' => User::where('role', 'user')->count(),
            'categories' => Category::where('is_active', true)->count(),
        ];

        $recentTickets = Ticket::with(['user', 'category', 'assignee'])->latest()->limit(8)->get();

        return view('admin.dashboard', compact('counts', 'recentTickets'));
    }
}
