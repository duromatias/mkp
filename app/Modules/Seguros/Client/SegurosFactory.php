<?php

namespace App\Modules\Seguros\Client;

class SegurosFactory {

    private static ?SegurosClient $instancia = null;

    static public function crear(): SegurosClient {
        if (static::$instancia === null) {
            $instance = new SegurosClient();
            $instance->loginConApiKey(config('seguros.api_key'));

            static::$instancia = $instance;
        }

        return static::$instancia;
    }
}
