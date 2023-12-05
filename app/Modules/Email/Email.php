<?php

namespace App\Modules\Email;

use App\Modules\Email\Emails\Generico;
use Illuminate\Support\Facades\Mail;

class Email {
    
    public static function enviar($destiatario, string $asunto, string $texto) {
        Mail::to($destiatario)->send(new Generico($asunto, $texto));
    }
}
