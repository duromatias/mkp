<?php

namespace App\Modules\Auth\Rules;

use App\Modules\Auth\Services\AuthService;
use Illuminate\Contracts\Validation\Rule;

class ValidPhoneOnboardingRule implements Rule
{
	private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
        return $this->authService->validarOnboardingTelefono($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El teléfono ingresado no es válido';
    }
}
