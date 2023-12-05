<?php

namespace App\Modules\Publicaciones\Ofertas\Requests;

use App\Modules\Publicaciones\Ofertas\Rules\PrecioOfertadoRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateOfertaRequest extends FormRequest
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
    	$publicacionId = $this->route('publicacion_id');

        return [
            'precio_ofertado' => ['required', 'numeric', new PrecioOfertadoRule($publicacionId)]
        ];
    }
}
