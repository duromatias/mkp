<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Rules\EmailExistenteRule;
use Illuminate\Foundation\Http\FormRequest;

class RecuperarPasswordRequest extends FormRequest
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
            'email' => ['required', new EmailExistenteRule()]
        ];
    }
}
