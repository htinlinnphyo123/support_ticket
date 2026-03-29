<?php

namespace Tests\Feature;

use App\Enums\ActiveStatus;
use App\Enums\UserType;
use App\Models\Organisation;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_only_see_their_organisation_tickets()
    {
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        $employee = User::factory()->create(['type' => UserType::Employee->value, 'organisation_id' => $org1->id, 'status' => ActiveStatus::Active->value]);
        $otherEmployee = User::factory()->create(['type' => UserType::Employee->value, 'organisation_id' => $org2->id, 'status' => ActiveStatus::Active->value]);

        $ticket1 = Ticket::factory()->create(['creator_id' => $employee->id]);
        $ticket2 = Ticket::factory()->create(['creator_id' => $otherEmployee->id]);

        $response = $this->actingAs($employee)->get(route('tickets.index'));

        $response->assertStatus(200);
        $response->assertSee($ticket1->title);
        $response->assertDontSee($ticket2->title);
    }

    public function test_agent_can_see_all_tickets()
    {
        $agent = User::factory()->create(['type' => UserType::Agent->value, 'status' => ActiveStatus::Active->value]);

        $org = Organisation::factory()->create();
        $ticket1 = Ticket::factory()->create(['creator_id' => User::factory()->create(['organisation_id' => $org->id])->id]);
        $ticket2 = Ticket::factory()->create(['creator_id' => User::factory()->create(['organisation_id' => $org->id])->id]);

        $response = $this->actingAs($agent)->get(route('tickets.index'));

        $response->assertStatus(200);
        $response->assertSee($ticket1->title);
        $response->assertSee($ticket2->title);
    }
}
