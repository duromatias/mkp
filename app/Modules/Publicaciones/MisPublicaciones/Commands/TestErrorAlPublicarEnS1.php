<?php

namespace App\Modules\Publicaciones\MisPublicaciones\Commands;

use App\Modules\Publicaciones\MisPublicaciones\Emails\ErrorAlPublicarEnS1;
use App\Modules\Publicaciones\Publicacion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestErrorAlPublicarEnS1 extends Command
{
    protected $signature = 'mis-publicaciones:test-error-al-publicar-en-s1';

    protected $description = 'Prueba de email error al publicar en s1';

    public function handle() {
        $publicacion = Publicacion::getById($this->ask('Publicacion Id'));
        $email       = $this->ask('Email');
        Mail::to($email)->send(new ErrorAlPublicarEnS1($publicacion));
    }
}
