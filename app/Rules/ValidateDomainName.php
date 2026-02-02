<?php

namespace App\Rules;

use App\Models\Domain;
use Illuminate\Contracts\Validation\Rule;

class ValidateDomainName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $host = strtolower($value);
        $domain = (substr($host, 0, 4) !== 'www.') ? $host : substr($host, 4, strlen($host));

        $data = Domain::where(function($query) use($value, $domain) {
                $query->where('name', $value)
                ->orWhere('name', $domain);
            })->whereHas('subscription', function($query) {
                $query->where('stripe_status', '!=', 'canceled');
            })->first();

        return ! ($data);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The domain name already exist";
    }
}
