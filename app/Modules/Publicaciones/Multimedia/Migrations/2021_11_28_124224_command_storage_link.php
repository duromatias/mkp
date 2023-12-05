<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CommandStorageLink extends Migration {

    public function up() {
        Artisan::call('storage:link');
    }

    public function down() {
        //
    }
}
