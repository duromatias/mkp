<?php

namespace App\Modules\Parametros\Modules\HomeCarouselSlides\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeCarouselSlideRequest extends FormRequest
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
            'titulo' => 'required|string',
			'detalle' => 'required|string',
			'link' => 'string',
			'imagen_desktop' => 'sometimes|required|file',
			'imagen_mobile' => 'sometimes|required|file',
			'orden' => 'required|integer'
        ];
    }
}
