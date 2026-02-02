<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InstallationController extends Controller
{
    public function index(Request $request) : Response
    {
        $domainsQuery = new Domain;

        if ($request->user_id) {
            $domainsQuery = $domainsQuery->where('user_id', $request->user_id);
        }

        return Inertia::render('Admin/Installation/Index', [
            'url' => config('app.url'),
            'domains' => $domainsQuery->select('id', 'name', 'token')->get(),
        ]);
    }
}
