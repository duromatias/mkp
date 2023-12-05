<?php

namespace App\Modules\Financiacion\Modules\Solicitud\Rules;

use App\Modules\Prendarios\Models\EstadoCivil;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class ExistingEstadoCivil implements Rule
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
			EstadoCivil::getOne(['Codigo' => $value]);
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
        return 'El estado civil es inexistente';
    }
}
