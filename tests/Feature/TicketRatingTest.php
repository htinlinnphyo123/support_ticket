<?php

namespace Tests\Feature;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
// use App\Mail\TicketResolvedMail; // You will need to create this!
use Tests\TestCase;

class TicketRatingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Step 1: The Database & Relationship
     * To make this pass, you need to create a `TicketRating` migration and model,
     * and add `public function rating()` to the Ticket model.
     */
    public function test_a_ticket_can_have_a_rating()
    {
        $ticket = Ticket::factory()->create();

        // We assume the relationship is called `rating()`
        $ticket->rating()->create([
            'score' => 5,
            'comment' => 'Great service!',
        ]);

        $this->assertDatabaseHas('ticket_ratings', [
            'ticket_id' => $ticket->id,
            'score' => 5,
            'comment' => 'Great service!',
        ]);
    }

    /**
     * Step 2: The Trigger
     * To make this pass, you'll need an Observer or Event on the Ticket
     * that listens for when the status changes to Resolved/Closed,
     * and then queues the Mail.
     */
    public function test_rating_email_is_sent_when_ticket_is_resolved()
    {
        Mail::fake();

        $ticket = Ticket::factory()->create([
            'status' => TicketStatus::Open->value,
        ]);

        // Act: Change status to resolved
        $ticket->update(['status' => TicketStatus::Resolved->value]);

        // Assert: The mail was sent to the ticket creator
        // Uncomment this once you create the Mailable!
        /*
        Mail::assertSent(TicketResolvedMail::class, function ($mail) use ($ticket) {
            return $mail->hasTo($ticket->creator->email);
        });
        */

        // Temporary assertion just to remind you to implement it
        $this->markTestIncomplete('Implement the TicketResolvedMail and Observer, then uncomment the assertion.');
    }

    /**
     * Step 4: The Happy Path Submission
     * To make this pass, create a route (e.g., /tickets/{ticket}/rate),
     * a controller, and save the data.
     */
    public function test_customer_can_submit_rating_with_valid_score()
    {
        $this->withoutExceptionHandling(); // Helps see the exact error when it fails

        $ticket = Ticket::factory()->create();
        $user = $ticket->creator;

        // Act: The user visits the rating endpoint (POST)
        $response = $this->actingAs($user)
            ->postJson(route('tickets.rate', $ticket), [
                'score' => 4,
                'comment' => 'Very helpful.',
            ]);

        // Assert: It was successful (e.g., 200 OK or a redirect)
        $response->assertStatus(200);

        // Assert: The rating exists in the database
        $this->assertDatabaseHas('ticket_ratings', [
            'ticket_id' => $ticket->id,
            'score' => 4,
        ]);
    }

    /**
     * Step 5: Validation - Sad Path
     * To make this pass, use a FormRequest or validate() in the controller.
     */
    public function test_score_must_be_between_1_and_5()
    {
        $ticket = Ticket::factory()->create();
        $user = $ticket->creator;

        // Try to submit a score of 6 (invalid)
        $response = $this->actingAs($user)
            ->postJson(route('tickets.rate', $ticket), [
                'score' => 6,
            ]);

        // Assert validation failed
        $response->assertJsonValidationErrors(['score']);
        $this->assertDatabaseMissing('ticket_ratings', ['ticket_id' => $ticket->id]);
    }

    /**
     * Step 6: Authorization & Duplicates
     * Only the ticket creator should be able to rate, and only once.
     */
    public function test_cannot_rate_same_ticket_twice()
    {
        $ticket = Ticket::factory()->create();
        $user = $ticket->creator;

        // Give it an initial rating
        $ticket->rating()->create(['score' => 5]);

        // Try to rate it again via the endpoint
        $response = $this->actingAs($user)
            ->postJson(route('tickets.rate', $ticket), [
                'score' => 3,
            ]);

        // Assert it returns a 403 Forbidden or 400 Bad Request
        $response->assertStatus(403);

        // Make sure it didn't overwrite the original score
        $this->assertEquals(5, $ticket->rating->score);
    }
}
