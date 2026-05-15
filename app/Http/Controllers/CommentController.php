<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Ticket $ticket)
    {
        Gate::authorize('view', $ticket); // Ensure they can even see the ticket to comment on it

        $data = $request->validated();

        // Force employees to always create public comments
        if ($request->user()->type->value === 'employee') {
            $data['is_public'] = true;
        } else {
            // Default to public if not explicitly set by the agent
            $data['is_public'] = $request->input('is_public', true);
        }

        $data['creator_id'] = $request->user()->id;

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('comments', 'public');
        }

        $ticket->comments()->create($data);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }
}
