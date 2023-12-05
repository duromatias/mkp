<?php

namespace App\Modules\Financiacion\Modules\Solicitud;

use App\Modules\Publicaciones\PublicacionesBusiness;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorAlSolicitarCuotasPorUsuario extends Mailable
{
	use Queueable, SerializesModels;

    private string $email;
	private int $publicacion_id;
    private string $publicacion_url;
    private string $agencia;
	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(string $email, int $publicacion_id, string $agencia)
	{
		$this->email = $email;
		$this->publicacion_id = $publicacion_id;
		$this->publicacion_url = PublicacionesBusiness::obtenerUrlMarketplace($publicacion_id);
        $this->agencia = $agencia;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('No se ha podido procesar la financiación')
			->markdown('ErrorAlSolicitarCuotasPorUsuario')
			->with([
                'email' => $this->email,
                'publicacion_id' => $this->publicacion_id,
                'publicacion_url' => $this->publicacion_url,
                'agencia' => $this->agencia,
			]);
	}
}
