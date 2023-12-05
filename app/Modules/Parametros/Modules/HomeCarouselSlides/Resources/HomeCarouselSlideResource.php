<?php

namespace App\Modules\Parametros\Modules\HomeCarouselSlides\Resources;

use App\Modules\Parametros\Modules\HomeCarouselSlides\Businesses\HomeCarouselSlideBusiness;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeCarouselSlideResource extends JsonResource
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
        	'titulo' => $this->titulo,
			'detalle' => $this->detalle,
			'link' => $this->link,
			'imagen_desktop' => HomeCarouselSlideBusiness::getImageUrl($this->imagen_desktop_file_name),
			'imagen_mobile' => HomeCarouselSlideBusiness::getImageUrl($this->imagen_mobile_file_name),
			'orden' => $this->orden
		];
    }
}
