<?php

namespace App\Modules\Users\Requests;

use App\Http\FormRequest;

class ActualizarUsuarioActualRequest extends FormRequest
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
            'nombre'        => 'sometimes|string',
            'dni'           => 'sometimes|string|nullable',
            'telefono'      => 'sometimes|integer|nullable',
            'placeId'       => 'sometimes|string|nullable',
            'calle'         => 'sometimes|string|nullable',
            'numero'        => 'sometimes|string|nullable',
            'codigo_postal' => 'string|nullable|required_unless:localidad,null',
            'localidad'     => 'sometimes|string|nullable',
            'provincia'     => 'sometimes|string|nullable',
            'latitud'       => 'sometimes|numeric|nullable',
            'longitud'      => 'sometimes|numeric|nullable',
        ];
    }
    
    public function messages() {
        return array_merge(parent::messages(), [
            'codigo_postal.required_unless' => 'El código postal es obligatorio',
        ]);
    }
}
