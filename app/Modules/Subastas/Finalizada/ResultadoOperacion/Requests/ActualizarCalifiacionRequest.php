<?php

namespace App\Modules\Subastas\Finalizada\ResultadoOperacion\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of ActualizarCalifiacionRequest
 *
 * @author manu
 */
class ActualizarCalifiacionRequest extends FormRequest{
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
            'calificacion'   => ['required', Rule::in(['1', '2','3','4','5'])],
        ];
    }
}
