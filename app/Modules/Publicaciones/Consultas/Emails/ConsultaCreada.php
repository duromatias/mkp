<?php

namespace App\Modules\Publicaciones\Consultas\Emails;

use App\Modules\Publicaciones\Consultas\Consulta;
use App\Modules\Publicaciones\Publicacion;
use Illuminate\Mail\Mailable;

class ConsultaCreada extends Mailable
{
	protected Publicacion $publicacion;
	protected Consulta $consulta;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Publicacion $publicacion, Consulta $consulta)
    {
        $this->publicacion = $publicacion;
        $this->consulta = $consulta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("CONSULTA POR {$this->publicacion->marca} {$this->publicacion->modelo} {$this->publicacion->año}")
			->markdown('ConsultaCreada')
			->with([
				'publicacion' => $this->publicacion,
				'consulta' => $this->consulta,
			]);
    }
}
