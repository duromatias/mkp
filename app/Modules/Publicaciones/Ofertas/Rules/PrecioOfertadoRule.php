<?php

namespace App\Modules\Publicaciones\Ofertas\Rules;

use App\Modules\Publicaciones\Ofertas\Businesses\OfertaBusiness;
use App\Modules\Publicaciones\Publicacion;
use Illuminate\Contracts\Validation\Rule;

class PrecioOfertadoRule implements Rule
{
	protected int $publicacionId;
	protected int $ofertaMinima;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $publicacionId)
	{
        $this->publicacionId = $publicacionId;
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
		$publicacion = Publicacion::getById($this->publicacionId);

		$this->ofertaMinima = OfertaBusiness::obtenerValorOfertaMinima($publicacion);

		return $value >= $this->ofertaMinima;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "La oferta mínima es de {$this->ofertaMinima}";
    }
}
