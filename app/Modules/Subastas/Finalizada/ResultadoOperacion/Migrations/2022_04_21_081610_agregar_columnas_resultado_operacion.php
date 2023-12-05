<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarColumnasResultadoOperacion extends Migration
{
    const enum1al5 = ['1', '2', '3', '4', '5'];
    
    public function up() {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->boolean   ('venta_realizada')                        ->nullable();              
            $table->boolean   ('compra_realizada')                       ->nullable();
            $table->text      ('observaciones_vendedor')                 ->nullable();               
            $table->text      ('observaciones_comprador')                ->nullable();                             
            $table->enum      ('calificacion_comprador', self::enum1al5) ->nullable();             
            $table->enum      ('calificacion_vendedor', self::enum1al5)  ->nullable(); 
            $table->foreignId ('oferta_ganadora_id')                     ->nullable() ->constrained('ofertas') ->unique();
        });
    }

    public function down() {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->dropForeign ('publicaciones_oferta_ganadora_id_foreign');            
            $table->dropIndex   ('publicaciones_oferta_ganadora_id_foreign');
            $table->dropColumn  ('venta_realizada');            
            $table->dropColumn  ('compra_realizada');            
            $table->dropColumn  ('observaciones_vendedor');            
            $table->dropColumn  ('observaciones_comprador');                
            $table->dropColumn  ('calificacion_comprador');        
            $table->dropColumn  ('calificacion_vendedor');              
            $table->dropColumn  ('oferta_ganadora_id');        

        });
    }
}
