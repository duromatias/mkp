<?php

namespace App\Modules\Users\Requests;

use App\Modules\Users\Models\Rol;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActualizarRolRequest extends FormRequest
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
            'rol_id' => ['required', Rule::in([Rol::USUARIO_AGENCIA, Rol::USUARIO_PARTICULAR])]
        ];
    }
}
