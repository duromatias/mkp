<?php

namespace App\Modules\Financiacion\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerificarFinanciacionRequest extends FormRequest
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
            'brand_name' => 'required|string',
			'model_name' => 'required|string',
			'year' => 'required|int'
        ];
    }
}
