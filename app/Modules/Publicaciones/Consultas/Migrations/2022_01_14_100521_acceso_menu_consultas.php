<?php

use App\Modules\Auth\Models\Acceso;
use App\Modules\Auth\Models\AccesosRoles;
use Illuminate\Database\Migrations\Migration;

class AccesoMenuConsultas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $acceso = Acceso::agregar('Consultas', '/consultas', 'question_answer', 8);
        AccesosRoles::agregar(2, $acceso->id);
        AccesosRoles::agregar(3, $acceso->id);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $acceso = Acceso::getOne([
            'descripcion' => 'Consultas'
        ]);

        $accesosRoles = AccesosRoles::listarTodos([
            'acceso_id' => $acceso->id
        ]);
        
        foreach ($accesosRoles as $accesoRol) {
            $accesoRol->borrar();
        }
        $acceso->borrar();
    }
}
