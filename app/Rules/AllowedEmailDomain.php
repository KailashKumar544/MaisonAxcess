<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllowedEmailDomain implements Rule
{
    /**
     * The allowed email domains.
     *
     * @var array
     */
    protected $allowedDomains;

    /**
     * Create a new rule instance.
     *
     * @param  array  $allowedDomains
     * @return void
     */
    public function __construct(array $allowedDomains)
    {
        $this->allowedDomains = $allowedDomains;
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
        // Get the domain part of the email address
        $emailParts = explode('@', $value);
        $domain = end($emailParts);

        // Check if the domain is in the list of allowed domains
        return in_array($domain, $this->allowedDomains);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Le domaine :attribute n\'est pas autorisé.';
    }
}

