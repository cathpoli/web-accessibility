<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Auth\Events\PasswordReset;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }

    public function create(Request $request)
    {
        $user = User::find($request->route('id'));

        /** Redirect if user not found or login if email verified */
        if (!$user || $user->hasVerifiedEmail()) {
            return redirect()->route('login');
        }
        
        /** Redirect if user to set up password*/
        return Inertia::render('Client/Auth/SetPassword', [
            'email' => $user->email,
            'id' => $user->id,
        ]);
    }
}
