<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_normal_user_cannot_access_admin_pages(): void
    {
        $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_can_assign_and_resolve_a_ticket_with_an_internal_note(): void
    {
        $admin = User::factory()->admin()->create();
        $ticket = Ticket::factory()->create(['status' => 'open']);

        $this->actingAs($admin)->put(route('admin.tickets.update', $ticket), [
            'status' => 'resolved',
            'priority' => 'high',
            'assigned_to' => $admin->id,
            'message' => 'Device replaced after hardware diagnostics.',
            'is_internal' => '1',
        ])->assertRedirect();

        $ticket->refresh();
        $this->assertSame('resolved', $ticket->status);
        $this->assertSame($admin->id, $ticket->assigned_to);
        $this->assertNotNull($ticket->resolved_at);
        $this->assertDatabaseHas('ticket_updates', [
            'ticket_id' => $ticket->id,
            'old_status' => 'open',
            'new_status' => 'resolved',
            'is_internal' => true,
        ]);
    }

    public function test_admin_can_create_update_and_delete_an_unused_category(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Audio Visual',
            'description' => 'Projectors and classroom equipment.',
        ])->assertRedirect();

        $category = Category::where('name', 'Audio Visual')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Classroom AV',
            'description' => 'Projectors and classroom sound equipment.',
            'is_active' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Classroom AV']);
        $this->actingAs($admin)->delete(route('admin.categories.destroy', $category))->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_with_tickets_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        Ticket::factory()->for($category)->create();

        $this->actingAs($admin)->delete(route('admin.categories.destroy', $category))->assertSessionHasErrors('category');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
