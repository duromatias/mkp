<?php

namespace App\Modules\Subastas\Rules;

use Illuminate\Contracts\Validation\Rule;

class ColisionFechas implements Rule
{
	public string $fechaInicioInscripcion;
	public string $fechaFinInscripcion;
	public string $fechaInicioOfertas;
	public string $fechaFinOfertas;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
    	string $fechaInicioInscripcion,
		string $fechaFinInscripcion,
		string $fechaInicioOfertas,
		string $fechaFinOfertas
	) {
    	$this->fechaInicioInscripcion = $fechaInicioInscripcion;
    	$this->fechaFinInscripcion = $fechaFinInscripcion;
    	$this->fechaInicioOfertas = $fechaInicioOfertas;
    	$this->fechaFinOfertas = $fechaFinOfertas;
	}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {

		return !(max($this->fechaInicioInscripcion, $this->fechaInicioOfertas) <= min($this->fechaFinInscripcion, $this->fechaFinOfertas));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Las fechas de inscripción y ofertas se solapan';
    }
}
