<?php

namespace App\Services;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    public function getTicketsForUser(User $user, array $filters = [])
    {
        $query = Ticket::with(['creator.organisation', 'agent:id,name']);

        if ($user->type->value === 'employee') {
            $query->whereHas('creator', function (Builder $q) use ($user) {
                $q->where('organisation_id', $user->organisation_id);
            });
        } else {
            if (! empty($filters['organisation_id'])) {
                $query->whereHas('creator', function (Builder $q) use ($filters) {
                    $q->where('organisation_id', $filters['organisation_id']);
                });
            }

            if (! empty($filters['agent_id'])) {
                if ($filters['agent_id'] === 'unassigned') {
                    $query->whereNull('agent_id');
                } else {
                    $query->where('agent_id', $filters['agent_id']);
                }
            }
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (! empty($filters['search'])) {
            $query->where('title', 'like', '%'.$filters['search'].'%');
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function getDashboardStats(User $user): array
    {
        $stats = [];
        $recentTicketsQuery = Ticket::with(['creator.organisation', 'agent:id,name'])->latest()->take(5);

        if ($user->type->value === 'agent') {
            // Agent sees global stats
            $stats['total_active'] = Ticket::whereNotIn('status', [TicketStatus::Resolved->value, TicketStatus::Closed->value])->count();
            $stats['unassigned'] = Ticket::whereNotIn('status', [TicketStatus::Resolved->value, TicketStatus::Closed->value])->whereNull('agent_id')->count();
            $stats['overdue'] = Ticket::whereNotIn('status', [TicketStatus::Resolved->value, TicketStatus::Closed->value])
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())->count();
        } else {
            // Employee sees org-specific stats
            $orgId = $user->organisation_id;

            $stats['total_active'] = Ticket::whereHas('creator', function (Builder $q) use ($orgId) {
                $q->where('organisation_id', $orgId);
            })->whereNotIn('status', [TicketStatus::Resolved->value, TicketStatus::Closed->value])->count();

            $stats['recently_resolved'] = Ticket::whereHas('creator', function (Builder $q) use ($orgId) {
                $q->where('organisation_id', $orgId);
            })->where('status', TicketStatus::Resolved->value)
                ->where('updated_at', '>=', now()->subDays(30))->count();

            // Limit recent tickets to their org
            $recentTicketsQuery->whereHas('creator', function (Builder $q) use ($orgId) {
                $q->where('organisation_id', $orgId);
            });
        }

        return [
            'stats' => $stats,
            'recent_tickets' => $recentTicketsQuery->get(),
        ];
    }

    public function createTicket(array $data, User $user)
    {
        $data['creator_id'] = $user->id;

        // Determine default priority if none is provided
        $priority = $data['priority'] ?? TicketPriority::Medium->value;
        $priorityEnum = TicketPriority::tryFrom($priority);

        $hoursToAdd = $priorityEnum->slaHours();

        $data['due_date'] = now()->addHours($hoursToAdd);

        if (isset($data['file'])) {
            $data = $this->handleFileUpload($data);
        }

        return Ticket::create($data);
    }

    public function updateTicket(Ticket $ticket, array $data, User $user)
    {
        // If priority is updated and it's different, recalculate the SLA based on the creation time
        if (isset($data['priority']) && $data['priority'] !== $ticket->priority->value) {
            $priorityEnum = TicketPriority::tryFrom($data['priority']);
            $hoursToAdd = $priorityEnum->slaHours();

            // Re-calculate the due date relative to when the ticket was originally created
            $data['due_date'] = $ticket->created_at->addHours($hoursToAdd);
        }

        $ticket->update($data);

        return $ticket;
    }

    protected function handleFileUpload(array $data): array
    {
        $file = $data['file'];
        $path = $file->store('tickets', 'public');

        $data['link'] = Storage::url($path);
        $data['file_type'] = $file->getClientOriginalExtension();

        unset($data['file']);

        return $data;
    }
}
