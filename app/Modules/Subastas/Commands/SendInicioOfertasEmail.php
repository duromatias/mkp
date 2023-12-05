<?php

namespace App\Modules\Subastas\Commands;

use App\Modules\Subastas\Emails\InicioOfertasMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendInicioOfertasEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subastas:send-inicio-ofertas-email {email} {name=DefaultName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar email de comienzo de ofertas a un email específico';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Mail::to($this->argument('email'))->send(new InicioOfertasMail($this->argument('name')));
    }
}
