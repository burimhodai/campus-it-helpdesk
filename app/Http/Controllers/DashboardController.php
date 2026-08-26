<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        if ($request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $tickets = $request->user()->tickets();
        $counts = [
            'total' => (clone $tickets)->count(),
            'open' => (clone $tickets)->where('status', 'open')->count(),
            'active' => (clone $tickets)->whereIn('status', ['in_progress', 'waiting_user'])->count(),
            'resolved' => (clone $tickets)->whereIn('status', ['resolved', 'closed'])->count(),
        ];

        $recentTickets = Ticket::with(['category', 'assignee'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard', compact('counts', 'recentTickets'));
    }
}
