<?php

namespace App\Modules\Onboarding\Commands;

use App\Modules\Auth\Services\LoginBusiness;
use App\Modules\Shared\Clients\OnboardingClient;
use Illuminate\Console\Command;

class LoginOnboarding extends Command {
    
    protected $signature   = 'onboarding:login';
    protected $description = 'Realizar un login contra onboarding para obtener el accces_token';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $email    = $this->ask('Email');
        $password = $this->ask('Clave');
        
        $ret      = app()->make(LoginBusiness::class)->loginOnboarding($email, $password);
        
        echo OnboardingClient::$accessToken;
    }
}
