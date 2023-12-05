<?php

namespace App\Modules\Auth\Tyc;
use Illuminate\Filesystem\FilesystemManager;

class TycBusiness
{
	private const RUTA = 'tyc/terminos-condiciones.html';

    static public function disk(): FilesystemManager {
        return app()->make(FilesystemManager::class);
    }

	static public function get(): string
	{
		if (!static::disk()->exists(self::RUTA)) {
			return '';
		}

		return static::disk()->get(self::RUTA);
	}

	/** Actualiza los TyC o crea el archivo si el mismo no existe */
	static public function update(string $terminosCondiciones)
	{
		static::disk()->put(self::RUTA, $terminosCondiciones);
	}
}