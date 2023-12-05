<?php

namespace App\Http;

use Illuminate\Foundation\Http\FormRequest as Base;
use Illuminate\Validation\Validator;

class FormRequest extends Base {
    
    public function withValidator(Validator $validator) {
        $validator->after(function () {
            $this->customValidations();
        });
    }
    
    public function customValidations() {
    }
    
    public function errors() {
        return $this->getValidatorInstance()->errors();
    }
}