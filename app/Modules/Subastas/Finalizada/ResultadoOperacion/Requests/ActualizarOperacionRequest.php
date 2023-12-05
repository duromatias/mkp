<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Modules\Subastas\Finalizada\ResultadoOperacion\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Description of ActualizarOperacionRequest
 *
 * @author manu
 */
class ActualizarOperacionRequest extends FormRequest
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
            'resultado'      => 'required|bool',
        ];
    }
}
