<?php

namespace Tests\Feature;

use App\Enums\ActiveStatus;
use App\Enums\SlaStatus;
use App\Enums\TicketPriority;
use App\Enums\UserType;
use App\Models\Organisation;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketSlaTest extends TestCase
{
    use RefreshDatabase;

    public function test_sla_due_date_calculated_on_creation()
    {
        $org = Organisation::factory()->create();
        $user = User::factory()->create(['type' => UserType::Employee->value, 'organisation_id' => $org->id, 'status' => ActiveStatus::Active->value]);
        
        $service = new TicketService();
        $ticket = $service->createTicket([
            'title' => 'Test',
            'description' => 'Test',
            'priority' => TicketPriority::High->value
        ], $user);

        $expected = now()->addHours(TicketPriority::High->slaHours())->startOfMinute();
        $this->assertEquals($expected->format('Y-m-d H:i'), $ticket->due_date->startOfMinute()->format('Y-m-d H:i'));
    }

    public function test_sla_due_date_recalculated_on_priority_update()
    {
        $agent = User::factory()->create(['type' => UserType::Agent->value, 'status' => ActiveStatus::Active->value]);
        $ticket = Ticket::factory()->create([
            'priority' => TicketPriority::Low->value,
            'created_at' => now()->subHours(2),
        ]);

        $service = new TicketService();
        $service->updateTicket($ticket, ['priority' => TicketPriority::High->value], $agent);

        $this->assertEquals($ticket->created_at->addHours(TicketPriority::High->slaHours())->startOfMinute()->format('Y-m-d H:i'), $ticket->fresh()->due_date->startOfMinute()->format('Y-m-d H:i'));
    }

    public function test_sla_status_resolves_correctly_based_on_time()
    {
        Carbon::setTestNow(now());

        $ticket = Ticket::factory()->create([
            'priority' => TicketPriority::High->value,
            'due_date' => now()->addHours(1)
        ]);
        $this->assertEquals(SlaStatus::DueSoon, $ticket->sla_status);

        $ticket->update([
            'due_date' => now()->subMinutes(10),
        ]);
        
        $this->assertEquals(SlaStatus::Overdue, $ticket->sla_status);
        
        Carbon::setTestNow();
    }
}
