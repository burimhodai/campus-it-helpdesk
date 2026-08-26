<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin() || $ticket->user_id === $user->id;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin() || ($ticket->user_id === $user->id && $ticket->isEditableByUser());
    }

    public function reply(User $user, Ticket $ticket): bool
    {
        return ($user->isAdmin() || $ticket->user_id === $user->id) && $ticket->status !== 'closed';
    }
}
