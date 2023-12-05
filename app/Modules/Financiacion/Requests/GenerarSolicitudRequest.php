<?php

namespace App\Modules\Financiacion\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerarSolicitudRequest extends FormRequest
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
			'cantidad_cuotas' => 'required|integer',
			'capital' => 'required|integer',
			'cotizacionSeguroId' => 'required|integer',
        ];
    }
}
