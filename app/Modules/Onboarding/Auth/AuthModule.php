<?php

namespace App\Modules\Onboarding\Auth;

use App\Base\Module;
use App\Modules\Onboarding\Auth\AuthController;

class AuthModule extends Module {
    
    public function bootApiRoutes() {
        $this->router()->post('login', [AuthController::class, 'login']);
    }
}
