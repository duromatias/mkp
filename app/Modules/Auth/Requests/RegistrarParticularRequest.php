<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Rules\AvailableEmailOnboardingRule;
use App\Modules\Shared\Clients\OnboardingClient;
use Illuminate\Foundation\Http\FormRequest;

class RegistrarParticularRequest extends FormRequest
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
    public function rules(OnboardingClient $onboardingClient)
    {
        return [
        	'email' => ['required', 'email', 'unique:usuarios,email', 'confirmed', new AvailableEmailOnboardingRule($onboardingClient)],
			'password' => 'required|min:8|confirmed',
			'nombre' => 'required'
        ];
    }
}
