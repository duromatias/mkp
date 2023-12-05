<?php

namespace App\Modules\Onboarding;

use App\Base\Module;
use App\Modules\Onboarding\Auth\AuthModule;

class OnboardingModule extends Module {
    
    public function register() {
        $this->provide(AuthModule::class);
    }
}
