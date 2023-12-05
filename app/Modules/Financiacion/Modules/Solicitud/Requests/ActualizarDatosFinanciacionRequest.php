<?php

namespace App\Modules\Financiacion\Modules\Solicitud\Requests;

use App\Modules\Financiacion\Modules\Solicitud\Rules\ExistingCodigoPostal;
use App\Modules\Financiacion\Modules\Solicitud\Rules\ExistingEstadoCivil;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActualizarDatosFinanciacionRequest extends FormRequest
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
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'dni' => 'required|string',
            'codigo_postal' => ['required', new ExistingCodigoPostal()],
            'localidad'     => ['required', 'string'],
            'provincia'     => ['required', 'string'],
            'sexo' => ['required', Rule::in(User::SEXOS)],
            'telefono' => 'required|string',
            'estado_civil_id' => ['required', new ExistingEstadoCivil()],
            'uso_vehiculo' => ['required', Rule::in(User::USOS_VEHICULO)]
        ];
    }

	public function messages() {
		return [
			'telefono.required' => 'El campo teléfono es requerido'
		];
	}
}
