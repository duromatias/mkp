<?php

namespace App\Modules\Financiacion\Modules\Solicitud\Rules;

use App\Modules\Prendarios\Models\Localidad;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class ExistingCodigoPostal implements Rule
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
        try {
			Localidad::getFirst(['codpost' => $value]);
			return true;
		} catch (Exception $exception) {
			return false;
		}
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El código postal es inexistente';
    }
}
