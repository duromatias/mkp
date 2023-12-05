<?php

namespace App\Modules\Subastas\Requests;

use App\Http\FormRequest;
use App\Modules\Subastas\Rules\ColisionFechas;
use App\Modules\Subastas\Rules\ColisionSubastaExistente;

class UpdateSubastaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [
			'fecha_inicio_inscripcion' => 'required|date',
			'fecha_fin_inscripcion' => 'required|date',
			'fecha_inicio_ofertas' => 'required|date',
			'fecha_fin_ofertas' => 'required|date'
		];
    }

	public function customValidations() {
		$fechaInicioInscripcion = $this->input('fecha_inicio_inscripcion');
		$fechaFinInscripcion = $this->input('fecha_fin_inscripcion');
		$fechaInicioOfertas = $this->input('fecha_inicio_ofertas');
		$fechaFinOfertas = 	$this->input('fecha_fin_ofertas');

		if (!$fechaInicioInscripcion || !$fechaFinInscripcion || !$fechaInicioOfertas || !$fechaFinOfertas) {
			// Errores asignados durante la validación
			return;
		}

		$colisionFechasRule = new ColisionFechas($fechaInicioInscripcion, $fechaFinInscripcion, $fechaInicioOfertas, $fechaFinOfertas);

		if (!$colisionFechasRule->passes('fecha_inicio_inscripcion', $fechaFinInscripcion)) {
			$this->errors()->add('fecha_inicio_inscripcion', $colisionFechasRule->message());
		}

		$colisionSubastaExistenteRule = new ColisionSubastaExistente(
            $fechaInicioInscripcion, 
            $fechaFinInscripcion, 
            $fechaInicioOfertas, 
            $fechaFinOfertas, 
            $this->route('id')
        );

		if (!$colisionSubastaExistenteRule->passes('fecha_inicio_inscripcion', $fechaFinInscripcion)) {
			$this->errors()->add('fechas', $colisionSubastaExistenteRule->message());
		}
	}
}
