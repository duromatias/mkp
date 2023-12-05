<?php

namespace App\Modules\Base\HttpLogger;

use App\Base\Module;
use App\Modules\Base\HttpLogger\Commands\BorrarRegistrosViejos;

class HttpLoggerModule extends Module {
    public function bootSchedule() {
        $this->scheduler()->command(BorrarRegistrosViejos::class)->dailyAt('03:00');
    }
}