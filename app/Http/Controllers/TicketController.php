<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = Ticket::with(['category', 'assignee'])
            ->where('user_id', $request->user()->id)
            ->search($request->string('search')->trim()->toString())
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('tickets.create', compact('categories'));
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $ticket = $request->user()->tickets()->create($request->validated());

        $ticket->updates()->create([
            'user_id' => $request->user()->id,
            'message' => 'Ticket created.',
            'new_status' => 'open',
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', "Ticket {$ticket->reference} was submitted.");
    }

    public function show(Request $request, Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load(['category', 'user', 'assignee']);
        $updates = $ticket->updates()
            ->with('user')
            ->when(! $request->user()->isAdmin(), fn ($query) => $query->where('is_internal', false))
            ->get();

        return view('tickets.show', compact('ticket', 'updates'));
    }

    public function edit(Ticket $ticket): View
    {
        $this->authorize('update', $ticket);

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('tickets.edit', compact('ticket', 'categories'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update($request->validated());

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket details updated.');
    }
}
