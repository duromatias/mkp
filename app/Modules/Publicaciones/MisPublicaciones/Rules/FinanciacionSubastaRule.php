<?php

namespace App\Modules\Publicaciones\MisPublicaciones\Rules;

use Illuminate\Contracts\Validation\Rule;

class FinanciacionSubastaRule implements Rule
{
	public ?int $subastaId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(?int $subastaId)
    {
        $this->subastaId = $subastaId;
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
        return !$value || $this->subastaId === null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Los vehículos que se incluyen en subastas no pueden ser financiados';
    }
}
