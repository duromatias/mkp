<?php

namespace App\Modules\Subastas\Cierre;

use App\Base\Module;
use App\Modules\Subastas\Cierre\Commands\NotificarCierre;
use App\Modules\Subastas\Cierre\Commands\TestCron;
use DateTime;

class CierreModule extends Module {
    
    public function bootSchedule() {
        $this->scheduler()->command(NotificarCierre::class, [
            (new DateTime())->modify('-1 day')->format('Y-m-d'),
        ])->dailyAt('9:00');
        
        // $this->scheduler()->command(TestCron::class, [
        //     (new DateTime())->modify('-1 day')->format('Y-m-d'),
        // ])->dailyAt('10:05');
    }
}
