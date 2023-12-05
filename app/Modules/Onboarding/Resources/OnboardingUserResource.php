<?php

namespace App\Modules\Onboarding\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OnboardingUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
        	'id' => $this->id,
			'email' => $this->email,
			'phone' => $this->phone,
			'business_id' => $this->business_id,
			'business' => new BusinessResource($this->whenLoaded('business')),
			'user_personal_data' => new UserPersonalDataResource($this->whenLoaded('userPersonalData')),
			'address' => new AddressResource($this->whenLoaded('address'))
		];
    }
}
