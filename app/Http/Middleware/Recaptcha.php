<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReCaptcha\ReCaptcha as GoogleRecaptcha;

class Recaptcha
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
        $secretKey = config('recaptcha.secret_key');

        $response = (new GoogleRecaptcha($secretKey))
            ->verify($request->input('recaptcha_token'), $request->ip());

        if (! $response->isSuccess()) {
            throw ValidationException::withMessages(['recaptcha_token' => 'Recaptcha failed. Please try again.']);
        }

        return $next($request);
    }
}
