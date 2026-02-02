<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RedirectOnRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /** proceed on local development */
        if (config('app.env') === 'local') return $next($request);

        /** proceed if not trying visit a page */
        if (! $request->isMethod('GET')) return $next($request);

        $dashboardDomain = config('app.dashboard_domain');
        $registerDomain = config('app.register_domain');
 
        /** redirect the user to register.aceada.com if user is trying to access register from admin.acecomply.com */
        if (Route::current()->uri() === 'register' && config('app.url') !== config('app.register_domain')) {
            return Redirect::to($registerDomain);
        }

        /** redirect the user to register.aceada.com if user is trying to access register from admin.acecomply.com */
        if (Route::current()->uri() === 'login' && config('app.url') === config('app.register_domain')) {
            return Redirect::to("{$dashboardDomain}/login");
        }

        return $next($request);
    }
}
