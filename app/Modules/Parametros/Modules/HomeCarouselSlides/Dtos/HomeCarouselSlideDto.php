<?php

namespace App\Modules\Parametros\Modules\HomeCarouselSlides\Dtos;

use App\Base\DataTransferObject;
use Illuminate\Http\UploadedFile;

class HomeCarouselSlideDto extends DataTransferObject
{
	public string $titulo;
	public string $detalle;
	public ?string $link;
	public int $orden;
	public ?UploadedFile $imagen_desktop;
	public ?UploadedFile $imagen_mobile;
}