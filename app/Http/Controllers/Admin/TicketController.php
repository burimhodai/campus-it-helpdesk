<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = Ticket::with(['user', 'category', 'assignee'])
            ->search($request->string('search')->trim()->toString())
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->string('priority')))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when($request->boolean('unassigned'), fn ($query) => $query->whereNull('assigned_to'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.tickets.index', compact('tickets', 'categories'));
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load(['user', 'category', 'assignee']);
        $updates = $ticket->updates()->with('user')->get();
        $admins = User::where('role', 'admin')->orderBy('name')->get();

        return view('admin.tickets.show', compact('ticket', 'updates', 'admins'));
    }

    public function update(AdminUpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validated();
        $oldStatus = $ticket->status;

        DB::transaction(function () use ($request, $ticket, $validated, $oldStatus): void {
            $ticket->update([
                'status' => $validated['status'],
                'priority' => $validated['priority'],
                'assigned_to' => $validated['assigned_to'] ?? null,
                'resolved_at' => in_array($validated['status'], ['resolved', 'closed'], true)
                    ? ($ticket->resolved_at ?? now())
                    : null,
            ]);

            if ($oldStatus !== $validated['status'] || filled($validated['message'] ?? null)) {
                $ticket->updates()->create([
                    'user_id' => $request->user()->id,
                    'message' => $validated['message'] ?? null,
                    'old_status' => $oldStatus !== $validated['status'] ? $oldStatus : null,
                    'new_status' => $oldStatus !== $validated['status'] ? $validated['status'] : null,
                    'is_internal' => $request->boolean('is_internal'),
                ]);
            }
        });

        return back()->with('success', 'Ticket management details saved.');
    }
}
