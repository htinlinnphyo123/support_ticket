<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
