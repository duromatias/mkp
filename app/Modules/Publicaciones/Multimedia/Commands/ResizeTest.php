<?php

namespace App\Modules\Publicaciones\Multimedia\Commands;

use App\Modules\Publicaciones\Multimedia\MultimediaBusiness;
use Illuminate\Console\Command;

class ResizeTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multimedia:resize-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $cardFilePath = '/home/kodear/Documents/kodear/decreditos/fotos_vehiculos/honda_civic_exl/redimensionar.png';
        MultimediaBusiness::redimensionar($cardFilePath, 348, 194);
    }
}
