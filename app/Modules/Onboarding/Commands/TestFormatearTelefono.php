<?php

namespace App\Modules\Onboarding\Commands;

use App\Modules\Onboarding\Models\Business;
use Illuminate\Console\Command;

class TestFormatearTelefono extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onboarding:test-formatearTelefono';

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
        $onboarding = "011 45249521";
        $whatsapp   = (new Business)->formatearTelefono($onboarding);
        
        echo "onboarding: {$onboarding}\n";
        echo "whatsapp  : {$whatsapp}\n";
    }
}
