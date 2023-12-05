<?php

namespace App\Modules\Publicaciones\Consultas\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearConsultaRequest extends FormRequest
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
            'publicacion_id' => 'required|integer',
        	'nombre' => 'required|string',
            'email' => 'required|string|email',
			'telefono' => 'required|integer',
			'texto' => 'required|string',
        ];
    }
}
