<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarColumnasLogHttp extends Migration {
    
    public function up() {
        Schema::table('log_http', function(Blueprint $table) {
            $table->integer('statusCode', false, true)->after('respuestaData')->nullable()->default(null);
            $table->integer('error',      false, true)->after('respuestaData')->nullable()->default(null);
        });
        
        Schema::table('log_http', function(Blueprint $table) {
            $table->index('statusCode');
            $table->index('error'     );
        });
    }

    public function down() {
        Schema::table('log_http', function(Blueprint $table) {
            $table->dropColumn(['statusCode', 'error']);
        });
    }
}
