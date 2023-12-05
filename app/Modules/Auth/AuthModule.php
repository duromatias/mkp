<?php

namespace App\Modules\Auth;

use App\Base\Module;
use App\Modules\Auth\Tyc\TycModule;
use Illuminate\Support\Facades\Route;

class AuthModule extends Module {
    
    public static function defineHttpRoutes() {
		TycModule::defineHttpRoutes();
	}
    
    public function register() {
    	$this->app->register(TycModule::class);
    }
}
