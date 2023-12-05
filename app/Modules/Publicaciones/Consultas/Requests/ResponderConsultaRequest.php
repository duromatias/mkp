<?php

namespace App\Modules\Publicaciones\Consultas\Requests;

use App\Modules\Publicaciones\Consultas\Consulta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResponderConsultaRequest extends FormRequest
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
        	'texto'  => 'string|nullable|required_if:estado,' . Consulta::ESTADO_RESUELTA,
            'estado' => ['required', Rule::in([Consulta::ESTADO_PENDIENTE, Consulta::ESTADO_RESUELTA])],
        ];
    }
}
