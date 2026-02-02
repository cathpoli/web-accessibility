<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    /**
     * @return App\Models\Domain
     * We should get this using the host global variable
     */
    protected $domain;

    /** we'll use this as basis to get the $domain and verify the token */
    protected $host;

    public function verify(Request $request)
    {
        $this->validateRequest($request);

        // equals to window.location.hostname from frontend since request referer is sometimes missing
        $this->host = Domain::formatHostname($request->host);

        // get domain model
        $this->domain = Domain::where('name', $this->host)->first();

        if ($this->allowWhitelistedDomains()) return $this->validClientDomain();

        $this->validateDomainSubscription($request);

        // This part is unnecessary but we'll just keep it
        $domainName = Domain::formatHostname($this->domain->name);
        if ($domainName == $this->host) return $this->validClientDomain();

        // fallback response
        $this->throwException();
    }

    public function allowWhitelistedDomains() : bool
    {
        /** REMINDER: comment this when testing on local */
        if (App::environment(['local'])) return true;

        /**
         * NOTE: APP_REGISTER_DOMAIN and APP_DASHBOARD_DOMAIN should also be added to domains
         *      table with is_whitelisted column set to true and everything else as null or their default value
         */
        return $this->domain->is_whitelisted ?? false;
    }

    public function validateRequest(Request $request)
    {
        $request->merge(['host' => Domain::formatHostname($request->host)]);

        $validator = Validator::make($request->all(), [
            'host' => 'required|exists:domains,name',
            // 'token' => 'required' // whitelisted domains do not provide token on their script
        ], [
            'host.required' => 'Domain is required.',
            'host.exists' => 'Domain is not registered.',
            // 'token.required' => 'Token is required.',
        ]);

        if ($validator->fails()) $this->throwException($validator->errors()->first());
    }

    public function validateDomainSubscription(Request $request)
    {
        if (!$this->domain || !$this->domain->subscription) {
            $this->throwException("Domain is not subscribed.");
        }

        if ($this->domain->subscription->stripe_status != 'active') {
            $this->throwException("Domain subscription is not active.");
        }

        if (! $request->token) {
            $this->throwException("Token is missing.");
        }

        if ($request->token !== $this->domain->token) {
            $this->throwException("Token provided does not match the one assigned for this domain.");
        }
    }

    public function validClientDomain()
    {
        return Response::json([
            'widget_configuration' => $this->domain->decoded_widget_configuration,
            'message' => config('app.name') . ": Thank you for using our services.",
        ], 200);
    }

    public function throwException($message = "Something went wrong.")
    {
        throw ValidationException::withMessages(['message' => config('app.name') . ": " . $message]);
    }
}
