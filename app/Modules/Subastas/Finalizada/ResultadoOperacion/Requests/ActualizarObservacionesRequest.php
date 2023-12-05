<?php

namespace App\Modules\Subastas\Finalizada\ResultadoOperacion\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarObservacionesRequest extends FormRequest
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
            'publicacion_id' => 'required|numeric',
            'observaciones'  => 'string|nullable',
        ];
    }
}
