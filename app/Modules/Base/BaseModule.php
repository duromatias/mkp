<?php

namespace App\Modules\Base;

use App\Base\Module;
use App\Modules\Base\HttpLogger\HttpLoggerModule;

class BaseModule extends Module {
    
    public function register(): void {
        $this->provide(HttpLoggerModule::class);
    }
}