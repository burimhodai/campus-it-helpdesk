<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_view_a_ticket(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('tickets.store'), [
            'category_id' => $category->id,
            'subject' => 'Printer does not accept print jobs',
            'description' => 'The printer in room B-214 shows online but every submitted print job remains in the queue.',
            'priority' => 'medium',
        ]);

        $ticket = Ticket::first();
        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertSame($user->id, $ticket->user_id);
        $this->assertStringStartsWith('HD-', $ticket->reference);
        $this->assertDatabaseHas('ticket_updates', ['ticket_id' => $ticket->id, 'new_status' => 'open']);
        $this->actingAs($user)->get(route('tickets.show', $ticket))->assertOk()->assertSee($ticket->reference);
    }

    public function test_user_cannot_view_or_edit_another_users_ticket(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $ticket = Ticket::factory()->for($owner)->create();

        $this->actingAs($otherUser)->get(route('tickets.show', $ticket))->assertForbidden();
        $this->actingAs($otherUser)->get(route('tickets.edit', $ticket))->assertForbidden();
    }

    public function test_user_can_edit_only_a_new_unassigned_ticket(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $ticket = Ticket::factory()->for($user)->for($category)->create(['status' => 'open']);

        $this->actingAs($user)->put(route('tickets.update', $ticket), [
            'category_id' => $category->id,
            'subject' => 'Updated subject for the support request',
            'description' => 'This description contains the corrected details required by the support team.',
            'priority' => 'high',
        ])->assertRedirect(route('tickets.show', $ticket));

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'priority' => 'high']);

        $ticket->update(['assigned_to' => User::factory()->admin()->create()->id]);
        $this->actingAs($user)->get(route('tickets.edit', $ticket->fresh()))->assertForbidden();
    }

    public function test_user_reply_cannot_be_marked_internal(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->for($user)->create();

        $this->actingAs($user)->post(route('tickets.updates.store', $ticket), [
            'message' => 'Here is the additional information you requested.',
            'is_internal' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('ticket_updates', [
            'ticket_id' => $ticket->id,
            'message' => 'Here is the additional information you requested.',
            'is_internal' => false,
        ]);
    }
}
