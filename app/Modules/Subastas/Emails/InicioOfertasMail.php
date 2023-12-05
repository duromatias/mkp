<?php

namespace App\Modules\Subastas\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InicioOfertasMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$subject = $this->name ? "{$this->name}, comienzan las subastas" : 'Hola, comienzan las subastas';

        return $this->subject($subject)
			->markdown('InicioOfertasMail')
        	->with([
        		'name' => $this->name
			]);
    }
}
