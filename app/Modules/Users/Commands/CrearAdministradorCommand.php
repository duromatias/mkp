<?php

namespace App\Modules\Users\Commands;

use App\Modules\Users\Models\User;
use Illuminate\Console\Command;

class CrearAdministradorCommand extends Command {
    
    protected $signature = 'usuarios:crear-administrador';
    protected $description = 'Crear un administrador';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $email    = $this->ask('Email');
        $nombre   = $this->ask('Nombre');
        $password = $this->ask('Password');
        $this->confirm("Confirma?");
        User::crearAdministrador($email, $nombre, $password);
        return 0;
    }
}
