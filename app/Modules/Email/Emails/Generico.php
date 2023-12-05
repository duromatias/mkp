<?php

namespace App\Modules\Email\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Generico extends Mailable {
    
    use Queueable, SerializesModels;
    
    private string $texto;

    public function __construct(string $asunto, string $texto){
        $this->subject = $asunto;
        $this->texto = $texto;
    }

    public function build() {
        return $this->subject($this->subject)
			->markdown('email-generico')
        	->with([
        		'texto' => $this->texto,
			]);
    }
}
