<?php

namespace App\Modules\Parametros\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParametroMassUpdateRequest extends FormRequest
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
        	'parametros' => 'required|array',
			'parametros.*.id' => 'required|integer',
			'parametros.*.descripcion' => 'required|string',
			'parametros.*.valor' => 'required|string'
        ];
    }
}
