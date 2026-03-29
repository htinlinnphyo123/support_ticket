<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use App\Http\Resources\TicketResource;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected TicketService $ticketService) {}

    public function index(Request $request)
    {
        $dashboardData = $this->ticketService->getDashboardStats($request->user());

        return Inertia::render('Dashboard', [
            'stats' => $dashboardData['stats'],
            'recent_tickets' => TicketResource::collection($dashboardData['recent_tickets']),
        ]);
    }
}
