<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedAccesos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('accesos')->insert([
        	[
        		'id' => 1,
        		'descripcion' => 'Publicar',
				'ruta' => '/publicar',
				'icono' => 'post_add',
				'orden' => 1,
			],
			[
				'id' => 2,
				'descripcion' => 'Mis Publicaciones',
				'ruta' => '/mis-publicaciones',
				'icono' => 'article',
				'orden' => 2
			],
			[
				'id' => 3,
				'descripcion' => 'Mis Compras',
				'ruta' => '/mis-compras',
				'icono' => 'shopping_bag',
				'orden' => 4
			],
			[
				'id' => 4,
				'descripcion' => 'Mis Ventas',
				'ruta' => '/mis-ventas',
				'icono' => 'paid',
				'orden' => 3,
			],
			[
				'id' => 5,
				'descripcion' => 'Mis Datos',
				'ruta' => '/mis-datos',
				'icono' => 'account_circle',
				'orden' => 7
			],
			[
				'id' => 6,
				'descripcion' => 'Consulta Crédito',
				'ruta' => '/consulta-credito',
				'icono' => 'payments',
				'orden' => 8

			],
			[
				'id' => 7,
				'descripcion' => 'Usuarios',
				'ruta' => '/usuarios',
				'icono' => 'group',
				'orden' => 9,
			],
			[
				'id' => 8,
				'descripcion' => 'Configuración',
				'ruta' => '/configuracion',
				'icono' => 'settings',
				'orden' => 10,
			],
			[
				'id' => 9,
				'descripcion' => 'Consultas',
				'ruta' => '/consultas',
				'icono' => 'help_outline',
				'orden' => 5,
			],
			[
				'id' => 10,
				'descripcion' => 'Subastas',
				'ruta' => '/subastas',
				'icono' => 'gavel',
				'orden' => 6,
			],
			[
				'id' => 11,
				'descripcion' => 'Términos y Condiciones',
				'ruta' => '/terminos-y-condiciones',
				'icono' => 'format_list_numbered',
				'orden' => 11
			]
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('accesos')->whereIn('id', [1,2,3,4,5,6,7,8,9,10,11])->delete();
    }
}
