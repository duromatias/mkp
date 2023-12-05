<?php

namespace App\Modules\Auth\Rules;

use App\Modules\Shared\Clients\OnboardingClient;
use Illuminate\Contracts\Validation\Rule;

class AvailableRazonSocialOnboardingRule implements Rule
{
	private OnboardingClient $onboardingClient;

	public function __construct(OnboardingClient $onboardingClient)
	{
		$this->onboardingClient = $onboardingClient;
	}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
		$response = $this->onboardingClient->get('/unavailable_business_names', [
			'name' => $value
		]);

		return !$response->json();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La razón social ya se encuentra en uso';
    }
}
