<?php

namespace App\Modules\Subastas\Requests;

use App\Modules\Subastas\Rules\ColisionFechas;
use App\Http\FormRequest;
use App\Modules\Subastas\Rules\ColisionSubastaExistente;


class CreateSubastaRequest extends FormRequest
{
	protected $stopOnFirstFailure = true;

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
            'fecha_inicio_inscripcion' => 'required|date_format:Y-m-d|after_or_equal:today',
			'fecha_fin_inscripcion' => 'required|date_format:Y-m-d|',
			'fecha_inicio_ofertas' => 'required|date_format:Y-m-d|after:fecha_inicio_inscripcion',
			'fecha_fin_ofertas' => 'required|date_format:Y-m-d'
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
			$this->errors()->add('fechas', $colisionFechasRule->message());
		}

		$colisionSubastaExistenteRule = new ColisionSubastaExistente($fechaInicioInscripcion, $fechaFinInscripcion, $fechaInicioOfertas, $fechaFinOfertas);

		if (!$colisionSubastaExistenteRule->passes('fecha_inicio_inscripcion', $fechaFinInscripcion)) {
			$this->errors()->add('fechas', $colisionSubastaExistenteRule->message());
		}
	}

	public function messages() {
		return [
			'fecha_inicio_inscripcion.after_or_equal' => 'La fecha debe ser igual o mayor a la fecha actual',
			'fecha_fin_inscripcion.after_or_equal' => 'La fecha debe ser igual o mayor a la fecha actual',
			'fecha_inicio_ofertas.after_or_equal' => 'La fecha debe ser igual o mayor a la fecha actual',
			'fecha_fin_ofertas.after_or_equal' => 'La fecha debe ser igual o mayor a la fecha actual',
		];
	}
}
