<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Organisation;
use App\Services\TicketService;
use App\Http\Resources\TicketResource;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function __construct(protected TicketService $ticketService) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Ticket::class);
        $tickets = $this->ticketService->getTicketsForUser($request->user(), $request->all());
        return Inertia::render('Tickets/Index', [
            'tickets' => TicketResource::collection($tickets),
            'filters' => $request->only(['search', 'status', 'priority', 'organisation_id', 'agent_id']),
            'organisations' => $request->user()->type->value === 'agent' ? Organisation::all(['id', 'name']) : [],
            'agents' => $request->user()->type->value === 'agent' ? User::where('type', 'agent')->get(['id', 'name']) : [],
            'statusOptions' => TicketStatus::toOptions(),
            'priorityOptions' => TicketPriority::toOptions(),
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Ticket::class);
        return Inertia::render('Tickets/Create');
    }

    public function store(TicketRequest $request)
    {
        Gate::authorize('create', Ticket::class);
        $this->ticketService->createTicket($request->validated(), $request->user());

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);
        
        $isAgent = request()->user()->type->value === 'agent';
        
        $ticket->load([
            'creator.organisation', 
            'agent',
            'comments' => function ($query) use ($isAgent) {
                if (!$isAgent) {
                    $query->where('is_public', true);
                }
                $query->oldest();
            },
            'comments.creator:id,name,type'
        ]);
        
        return Inertia::render('Tickets/Show', [
            'ticket' => (new TicketResource($ticket))->resolve(),
            'agents' => request()->user()->type->value === 'agent' ? User::where('type', 'agent')->get(['id', 'name']) : [],
            'statusOptions' => TicketStatus::toOptions(),
            'priorityOptions' => TicketPriority::toOptions(),
        ]);
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);
        $this->ticketService->updateTicket($ticket, $request->validated(), $request->user());

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
