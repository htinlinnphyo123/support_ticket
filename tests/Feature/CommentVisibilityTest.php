<?php

namespace Tests\Feature;

use App\Enums\ActiveStatus;
use App\Enums\UserType;
use App\Models\Comment;
use App\Models\Organisation;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_cannot_see_internal_notes()
    {
        $org = Organisation::factory()->create();
        $employee = User::factory()->create(['type' => UserType::Employee->value, 'organisation_id' => $org->id, 'status' => ActiveStatus::Active->value]);
        $agent = User::factory()->create(['type' => UserType::Agent->value, 'status' => ActiveStatus::Active->value]);

        $ticket = Ticket::factory()->create(['creator_id' => $employee->id]);

        $publicComment = Comment::create([
            'ticket_id' => $ticket->id,
            'creator_id' => $agent->id,
            'description' => 'This is a public reply',
            'is_public' => true,
        ]);

        $internalNote = Comment::create([
            'ticket_id' => $ticket->id,
            'creator_id' => $agent->id,
            'description' => 'This is a secret note',
            'is_public' => false,
        ]);

        $response = $this->actingAs($employee)->get(route('tickets.show', $ticket));

        $response->assertStatus(200);
        $response->assertSee('This is a public reply');
        $response->assertDontSee('This is a secret note');
    }

    public function test_agent_can_see_internal_notes()
    {
        $agent = User::factory()->create(['type' => UserType::Agent->value, 'status' => ActiveStatus::Active->value]);
        $org = Organisation::factory()->create();
        $employee = User::factory()->create(['organisation_id' => $org->id]);
        $ticket = Ticket::factory()->create(['creator_id' => $employee->id]);

        $internalNote = Comment::create([
            'ticket_id' => $ticket->id,
            'creator_id' => $agent->id,
            'description' => 'This is a secret note',
            'is_public' => false,
        ]);

        $response = $this->actingAs($agent)->get(route('tickets.show', $ticket));

        $response->assertStatus(200);
        $response->assertSee('This is a secret note');
    }
}
