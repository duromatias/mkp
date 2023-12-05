<?php

namespace App\Modules\Parametros\Modules\HomeCarouselSlides\Models;

use App\Base\Repository\ModelRepository;
use Illuminate\Database\Eloquent\Builder;
use function in_array;

class HomeCarouselSlide extends ModelRepository
{
	protected $table = 'home_carousel_slides';
	protected $guarded = [];
	public $timestamps = false;

	public static function aplicarFiltros(Builder $query, array $filtros) {
		foreach ($filtros as $key => $value) {
			if (in_array($key, ['id'])) {
				$query->where($key, '=', $value);
			}
		}
	}
}