<?php

namespace App\Modules\Onboarding\Auth;

use App\Http\FormRequest;

class LoginRequest extends FormRequest {
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'email' => 'required|string',
        ];
    }
    
    public function customValidations() {
        $errors = $this->getValidatorInstance()->errors();
        $valor = $this->header('x-authorization-dc');
        if (empty($valor)) {
            $errors->add('x-authorization-dc','Falta encabezado requerido');
        }
    }
}
