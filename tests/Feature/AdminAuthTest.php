<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_can_login_and_access_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin12345'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'admin12345',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Admin Dashboard');
    }

    public function test_authenticated_admin_can_open_project_creation_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin12345'),
        ]);

        $response = $this->actingAs($user)->get('/projects/create');

        $response->assertStatus(200);
        $response->assertSee('Create Project');
    }

    public function test_admin_can_create_client_project_bank_and_invoice_with_real_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin12345'),
        ]);

        $this->actingAs($user)->post('/clients/create', [
            'name' => 'GreenTech Ltd.',
            'company' => 'GreenTech',
            'email' => 'client@example.com',
            'phone' => '+880123456789',
            'address' => 'Dhaka',
        ])->assertRedirect('/clients/create');

        $this->actingAs($user)->post('/banks/create', [
            'name' => 'DBBL',
            'account_number' => '1234567890',
            'branch' => 'Dhanmondi',
            'notes' => 'Primary account',
        ])->assertRedirect('/banks/create');

        $client = Client::where('email', 'client@example.com')->firstOrFail();

        $this->actingAs($user)->post('/projects/create', [
            'client_id' => $client->id,
            'name' => 'ERP Web Portal',
            'type' => 'Software Project',
            'project_value' => 250000,
            'description' => 'Accounting management app',
        ])->assertRedirect('/projects/create');

        $project = Project::where('name', 'ERP Web Portal')->firstOrFail();
        $bank = Bank::where('name', 'DBBL')->firstOrFail();

        $response = $this->actingAs($user)->post('/invoices/create', [
            'client_id' => $client->id,
            'project_id' => $project->id,
            'bank_id' => $bank->id,
            'invoice_number' => 'INV-001',
            'invoice_date' => '2026-07-11',
            'amount' => 250000,
            'paid_amount' => 150000,
            'handover_to' => 'Rahim',
            'description' => 'Partial payment received',
            'status' => 'Partial',
        ]);

        $response->assertRedirect('/invoices/create');

        $this->assertDatabaseHas('invoices', ['invoice_number' => 'INV-001']);
        $this->assertDatabaseHas('projects', ['name' => 'ERP Web Portal']);

        $invoice = Invoice::where('invoice_number', 'INV-001')->firstOrFail();
        $this->assertEquals(100000.0, $invoice->pending_amount);
        $list = $this->actingAs($user)->get('/invoices');
        $list->assertStatus(200);
        $list->assertSee('INV-001');
        $list->assertSee('GreenTech Ltd.');
        $list->assertSee('ERP Web Portal');
    }
}
