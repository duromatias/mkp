<?php

use App\Modules\Publicaciones\Publicacion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id');
			$table->foreign('usuario_id')->references('id')->on('usuarios');

			$table->unsignedBigInteger('tipo_combustible_id');
			$table->foreign('tipo_combustible_id')->references('id')->on('tipos_combustible');

			$table->string('marca');
			$table->string('modelo');
			$table->integer('año');
			$table->string('color');
			$table->enum('condicion', [Publicacion::CONDICION_USADO, Publicacion::CONDICION_0KM]);
			$table->integer('kilometros')->nullable();
			$table->enum('puertas', [Publicacion::PUERTAS_2, Publicacion::PUERTAS_3, Publicacion::PUERTAS_4, Publicacion::PUERTAS_5]);
			$table->string('descripcion')->nullable();
			$table->enum('moneda', [Publicacion::MONEDA_PESOS, Publicacion::MONEDA_DOLARES]);
			$table->decimal('precio');
			$table->string('calle')->nullable();
			$table->string('numero')->nullable();
			$table->string('localidad');
			$table->string('provincia');
			$table->string('codigo_postal');
			$table->decimal('latitud')->nullable();
			$table->decimal('longitud')->nullable();
			$table->enum('estado', [Publicacion::ESTADO_ACTIVA, Publicacion::ESTADO_VENDIDO, Publicacion::ESTADO_ELIMINADA]);
			$table->decimal('precio_compra_directa');
			$table->decimal('precio_sugerido');

			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicaciones');
    }
}
