<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Client/Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'registerDomain' => config('app.register_domain')
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $allowableUserTypes = [$request->type];

        if ($request->type == 'client') {
            $allowableUserTypes[] = 'member';
        }

        /** Throw error when client or member tried to authenticated on admin login form, vice versa */
        if (!in_array($request->user()->type, $allowableUserTypes)) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        /** Redirect admin to login if email not verified */
        if ($request->user()->type == 'member' && !$request->user()->hasVerifiedEmail()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        /** Redirect admin to admin dashboard */
        if ($request->user()->type == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Login user using single user token
     */
    public function singleSignOn(Request $request, string $token)
    {
        $user = User::where('sso_token', $token)->first();

        if (! $user) abort(404, 'User not found');

        /** delete sso_token is it cannot be used again; potentially by other people */
        $user->sso_token = null;
        $user->save();

        Auth::login($user);

        return Redirect::to('dashboard');
    }
}
