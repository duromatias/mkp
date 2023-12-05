<?php

namespace App\Modules\Base\HttpLogger\Commands;

use App\Modules\Base\HttpLogger\HttpLogger;
use Illuminate\Console\Command;

class BorrarRegistrosViejos extends Command {
    
    protected $signature = 'base:http-loger:borrar-registros-viejos';
    protected $description = 'Command description';

    public function handle() {
        HttpLogger::borrarRegistrosViejos();
    }
}
