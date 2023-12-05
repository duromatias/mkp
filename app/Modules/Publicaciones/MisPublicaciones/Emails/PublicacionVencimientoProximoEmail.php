<?php

namespace App\Modules\Publicaciones\MisPublicaciones\Emails;

use App\Modules\Publicaciones\Publicacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PublicacionVencimientoProximoEmail extends Mailable
{
    use Queueable, SerializesModels;

    private Publicacion $publicacion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Publicacion $publicacion)
    {
        $this->publicacion = $publicacion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$marca = $this->publicacion->marca;
    	$modelo = $this->publicacion->modelo;
    	$año = $this->publicacion->año;

        return $this
			->subject("Recordá Renovar o Finalizar el aviso de tu {$marca} {$modelo} {$año}")
			->markdown('PublicacionVencimientoProximo')
			->with([
				'publicacion' => $this->publicacion
			]);
    }
}
