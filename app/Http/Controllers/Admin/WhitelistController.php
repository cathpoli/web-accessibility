<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Whitelists\StoreRequest;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class WhitelistController extends Controller
{
    public function index(Request $request)
    {
        $firstPartyDomains = [];
        $domainsQuery = Domain::whitelisted();

        if ($registerUrl  = config('app.register_domain')) {
            $registerDomain = parse_url($registerUrl, PHP_URL_HOST);
            $firstPartyDomains[] = $registerDomain;
        }

        if ($dashboardUrl  = config('app.dashboard_domain')) {
            $dashboardDomain = parse_url($dashboardUrl, PHP_URL_HOST);
            $firstPartyDomains[] = $dashboardDomain;
        }

        /**
         * do not include register.aceada.com and admin.acecomply.com, they should not be considered as 'whitelist'
         * since they are first party.
         */
        $domainsQuery = $domainsQuery->whereNotIn('name', $firstPartyDomains);

        return Inertia::render('Admin/Whitelists/Index', [
            'url' => config('app.url'),
            'whitelists' => $domainsQuery->paginate(),
        ]);
    }

    public function installation(Domain $domain)
    {
        return Inertia::render('Admin/Whitelists/Installation', [
            'domain' => $domain,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Whitelists/Create');
    }

    public function store(StoreRequest $request)
    {
        $domain = new Domain();
        $domain->name = Domain::formatHostname($request->domain); // remove www.
        $domain->is_whitelisted = true;
        $domain->save();

        return Redirect::to('/admin/whitelists');
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();

        return Redirect::back();
    }
}
