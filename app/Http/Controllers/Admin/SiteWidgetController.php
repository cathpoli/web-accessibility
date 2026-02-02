<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SiteWidgetController extends Controller
{
    public function index()
    {
        $dashboardUrl  = config('app.dashboard_domain');
        $dashboardDomain = parse_url($dashboardUrl, PHP_URL_HOST);

        $domain = Domain::where('name', $dashboardDomain)->select('id', 'name', 'token')->first();

        return Inertia::render('Admin/SiteWidget/Index', [
            'domain' => $domain,
        ]);
    }
}
