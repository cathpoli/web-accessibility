<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Domains\SetWidgetRequest;
use App\Jobs\TakeDomainBrowsershotJob;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class WidgetController extends Controller
{
    public function show(Domain $domain)
    {
        $config = $domain->widget_configurations;

        return Response::json([
            'preview_img' => $domain->preview_img,
            'widget_configuration' => $config ? json_decode($config) : null
        ]);
    }

    /**
     * Handles updating and storing
     */
    public function store(Domain $domain, SetWidgetRequest $request)
    {
        $config = json_encode([
            'triggerColor' => $request->triggerColor,
            'triggerPositionX' => $request->triggerPositionX,
            'triggerPositionY' => $request->triggerPositionY,
            'triggerIcon' => $request->triggerIcon,
            'triggerIconColor' => $request->triggerIconColor,
            'widgetHeroTitleColor' => $request->widgetHeroTitleColor,
            'themeColor' => $request->themeColor,
        ]);

        $domain->widget_configurations = $config;
        $domain->save();

        /** check if admin is customizing site widget */
        $dashboardUrl  = config('app.dashboard_domain');
        $dashboardDomain = parse_url($dashboardUrl, PHP_URL_HOST);

        if ($domain->name == $dashboardDomain) {
            /** also update register.aceada.com's widget */
            $registerUrl  = config('app.register_domain');
            $registerDomain = parse_url($registerUrl, PHP_URL_HOST);

            Domain::where('name', $registerDomain)
                    ->update(['widget_configurations' => $config]);
        }

        return Redirect::back();
    }

    public function takeDomainBrowsershot(Domain $domain)
    {
        $method = App::environment('local') ? 'dispatchNow' : 'dispatch';
        TakeDomainBrowsershotJob::{$method}($domain->id);

        return Response::json(['success' => true]);
    }
}
