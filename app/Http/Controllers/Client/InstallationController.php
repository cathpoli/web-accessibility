<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InstallationController extends Controller
{
    public function index(Request $request) : Response
    { 
        $domains = $request->user()->domains()->select('id', 'name', 'token')->get();

        // member can see the domains of the owner
        if ($request->user()->type == 'member') {
            $domains = $request->user()->owner->domains()->select('id', 'name', 'token')->get();
        }
        
        return Inertia::render('Client/Installation/Index', [
            'url' => config('app.url'),
            'domains' => $domains,
        ]);
    }
}
