<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Domain;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Dashboard', [
            'total_clients' => User::clients()->count(),
            'paying_clients' => User::payingClients()->count(),
            'active_domains' => Domain::activeDomains()->count(),
        ]);
    }
}
