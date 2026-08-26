<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@campus.test'],
            ['name' => 'IT Administrator', 'password' => 'Admin123!', 'role' => 'admin']
        );

        $technician = User::updateOrCreate(
            ['email' => 'support@campus.test'],
            ['name' => 'Support Technician', 'password' => 'Support123!', 'role' => 'admin']
        );

        $user = User::updateOrCreate(
            ['email' => 'student@campus.test'],
            ['name' => 'Demo Student', 'password' => 'User123!', 'role' => 'user']
        );

        $employee = User::updateOrCreate(
            ['email' => 'employee@campus.test'],
            ['name' => 'Demo Employee', 'password' => 'User123!', 'role' => 'user']
        );

        $categories = collect([
            ['name' => 'Hardware', 'slug' => 'hardware', 'description' => 'Computers, monitors, printers, and physical equipment.'],
            ['name' => 'Software', 'slug' => 'software', 'description' => 'Installed applications, updates, and software errors.'],
            ['name' => 'Network', 'slug' => 'network', 'description' => 'Wi-Fi, wired connections, VPN, and internet access.'],
            ['name' => 'Account Access', 'slug' => 'account-access', 'description' => 'Login, password, permissions, and account problems.'],
        ])->mapWithKeys(function (array $data): array {
            $category = Category::updateOrCreate(['slug' => $data['slug']], [...$data, 'is_active' => true]);

            return [$data['slug'] => $category];
        });

        $wifi = Ticket::firstOrCreate(
            ['reference' => 'HD-260826-1001'],
            [
                'user_id' => $user->id,
                'category_id' => $categories['network']->id,
                'assigned_to' => $admin->id,
                'subject' => 'Wi-Fi disconnects in the library',
                'description' => 'The campus Wi-Fi disconnects every few minutes on the second floor of the library. I tested the same laptop in the cafeteria and it stayed connected.',
                'priority' => 'high',
                'status' => 'in_progress',
            ]
        );
        $wifi->updates()->firstOrCreate(
            ['message' => 'Ticket created.'],
            ['user_id' => $user->id, 'new_status' => 'open']
        );
        $wifi->updates()->firstOrCreate(
            ['message' => 'Assigned to network support. We are checking the access point on the second floor.'],
            ['user_id' => $admin->id, 'old_status' => 'open', 'new_status' => 'in_progress']
        );

        $mouse = Ticket::firstOrCreate(
            ['reference' => 'HD-260826-1002'],
            [
                'user_id' => $employee->id,
                'category_id' => $categories['hardware']->id,
                'assigned_to' => $technician->id,
                'subject' => 'Office mouse stops responding',
                'description' => 'The USB mouse in office B-214 stops responding after several minutes. Reconnecting it works briefly, and another USB port has the same problem.',
                'priority' => 'medium',
                'status' => 'waiting_user',
            ]
        );
        $mouse->updates()->firstOrCreate(
            ['message' => 'Could you confirm the asset number printed on the bottom of the mouse?'],
            ['user_id' => $technician->id, 'old_status' => 'in_progress', 'new_status' => 'waiting_user']
        );

        $software = Ticket::firstOrCreate(
            ['reference' => 'HD-260826-1003'],
            [
                'user_id' => $user->id,
                'category_id' => $categories['software']->id,
                'assigned_to' => null,
                'subject' => 'Required statistics application will not open',
                'description' => 'The statistics application closes immediately after the loading screen in computer lab C-03. It worked during the previous class.',
                'priority' => 'urgent',
                'status' => 'open',
            ]
        );
        $software->updates()->firstOrCreate(
            ['message' => 'Ticket created.'],
            ['user_id' => $user->id, 'new_status' => 'open']
        );

        $resolved = Ticket::firstOrCreate(
            ['reference' => 'HD-260826-1004'],
            [
                'user_id' => $employee->id,
                'category_id' => $categories['account-access']->id,
                'assigned_to' => $admin->id,
                'subject' => 'Password reset for the staff portal',
                'description' => 'My staff portal password expired and the self-service reset link did not arrive in my inbox.',
                'priority' => 'low',
                'status' => 'resolved',
                'resolved_at' => now()->subDay(),
            ]
        );
        $resolved->updates()->firstOrCreate(
            ['message' => 'The account was verified and a new reset email was issued successfully.'],
            ['user_id' => $admin->id, 'old_status' => 'in_progress', 'new_status' => 'resolved']
        );
    }
}
