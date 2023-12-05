<?php

namespace App\Modules\Users\Emails;

use Illuminate\Mail\Mailable;

class SolicitudBajaMail extends Mailable
{
    private string $userEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Solicitud de baja: {$this->userEmail}")
			->markdown('SolicitudBajaMail')
			->with(['userEmail' => $this->userEmail]);
    }
}
