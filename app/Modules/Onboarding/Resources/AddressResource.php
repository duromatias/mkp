<?php

namespace App\Modules\Onboarding\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
        	'street'      => $this->street,
			'number'      => $this->number,
			'locality'    => $this->locality,
			'postal_code' => $this->postal_code,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
			'province'    => new ProvinceResource($this->whenLoaded('province'))
		];
    }
}
