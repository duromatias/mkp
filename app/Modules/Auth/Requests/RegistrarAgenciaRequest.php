<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Agencias\Business\AgenciasBusiness;
use App\Modules\Auth\Rules\AvailableEmailOnboardingRule;
use App\Modules\Auth\Rules\AvailableRazonSocialOnboardingRule;
use App\Modules\Auth\Rules\ValidCuitOnboardingRule;
use App\Modules\Auth\Rules\ValidPhoneOnboardingRule;
use App\Modules\Auth\Services\AuthService;
use App\Modules\Shared\Clients\OnboardingClient;
use Illuminate\Foundation\Http\FormRequest;

class RegistrarAgenciaRequest extends FormRequest
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
    public function rules(OnboardingClient $onboardingClient, AuthService $authService, AgenciasBusiness $agenciasBusiness)
    {
        return [
			'email' => ['required', 'email', 'unique:usuarios,email', 'confirmed', new AvailableEmailOnboardingRule($onboardingClient)],
			'password' => 'required|min:8|confirmed',
			'cuit' => ['required' , 'string', new ValidCuitOnboardingRule($agenciasBusiness)],
			'razon_social' => ['required', new AvailableRazonSocialOnboardingRule($onboardingClient)],
			'calle' => 'required',
			'numero' => 'integer',
			'localidad' => 'required|string',
			'provincia' => 'required|string',
			'latitud' => 'required|numeric',
			'longitud' => 'required|numeric',
			'codigo_postal' => 'required|string',
			'telefono' => ['required', 'string', new ValidPhoneOnboardingRule($authService)]
        ];
    }
}
