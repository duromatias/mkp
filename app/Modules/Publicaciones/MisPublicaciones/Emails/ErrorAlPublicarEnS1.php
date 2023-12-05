<?php

namespace App\Modules\Publicaciones\MisPublicaciones\Emails;

use App\Modules\Publicaciones\Publicacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorAlPublicarEnS1 extends Mailable
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
		return $this
			->subject("No se pudo registrar la publicación {$this->publicacion->id} en la web de agencias")
			->markdown('ErrorAlPublicarEnS1')
			->with([
                'mensaje'     => "No se pudo registrar la publicación {$this->publicacion->id} en la web de agencias",
				'publicacion' => $this->publicacion
			]);
	}
}
