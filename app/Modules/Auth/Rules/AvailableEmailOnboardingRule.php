<?php

namespace App\Modules\Auth\Rules;

use App\Modules\Auth\Services\AuthService;
use Illuminate\Contracts\Validation\Rule;

class AvailableEmailOnboardingRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
		return AuthService::puedeRegistrarEmailEnOnboarding($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El email ya se encuentra en uso';
    }
}
