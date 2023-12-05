<?php

namespace App\Modules\Subastas\Emails;

use App\Modules\Subastas\Subasta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InicioInscripcionMail extends Mailable
{
	use Queueable, SerializesModels;

	private string $name;
	private Subasta $subasta;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(Subasta $subasta, string $name)
	{
		$this->subasta = $subasta;
		$this->name = $name;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('¡No te quedes afuera! Sumá tu auto a la subasta!')
			->markdown('InicioInscripcionMail')
			->with([
				'subasta' => $this->subasta,
				'name' => $this->name
			]);
	}
}
