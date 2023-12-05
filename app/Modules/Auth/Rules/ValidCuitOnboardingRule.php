<?php

namespace App\Modules\Auth\Rules;

use App\Modules\Agencias\Business\AgenciasBusiness;
use Illuminate\Contracts\Validation\Rule;

class ValidCuitOnboardingRule implements Rule
{
	private AgenciasBusiness $agenciasBusiness;

    public function __construct(AgenciasBusiness $agenciasBusiness)
    {
        $this->agenciasBusiness = $agenciasBusiness;
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
        return $this->agenciasBusiness->validarCuit($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El cuit ingresado es inválido';
    }
}
