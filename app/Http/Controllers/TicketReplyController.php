<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;

class TicketReplyController extends Controller
{
    public function store(StoreReplyRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->updates()->create([
            'user_id' => $request->user()->id,
            'message' => $request->validated('message'),
            'is_internal' => $request->user()->isAdmin() && $request->boolean('is_internal'),
        ]);

        return back()->with('success', 'Your update was added.');
    }
}
