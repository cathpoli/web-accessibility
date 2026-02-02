<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventUnauthorizedPageVisits
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
        /** Prevent client and member from accessing admin routes/pages, vice versa */
        if (Auth::check()) {
            $userType = $request->user()->type;

            if (($userType === 'client' || $userType === 'member') && $request->is('admin/*')) {
                abort(401, "Clients cannot visit this page.");
            }

            if ($userType === 'admin' && ! $request->is('admin/*') && ! $request->is('logout')) {
                abort(401, 'You are currently logged in as an admin, please logout first.');
            }
        }

        return $next($request);
    }
}
