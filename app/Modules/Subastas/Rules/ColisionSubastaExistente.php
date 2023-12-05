<?php

namespace App\Modules\Subastas\Rules;

use App\Modules\Subastas\Subasta;
use Illuminate\Contracts\Validation\Rule;

class ColisionSubastaExistente implements Rule
{
	public string $fecha_inicio_inscripcion;
	public string $fecha_fin_inscripcion;
	public string $fecha_inicio_ofertas;
	public string $fecha_fin_ofertas;
	public string $subasta_id;
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct(
		string $fecha_inicio_inscripcion,
		string $fecha_fin_inscripcion,
		string $fecha_inicio_ofertas,
		string $fecha_fin_ofertas,
		string $excluir_subasta_id = null
	) {
		$this->fecha_inicio_inscripcion = $fecha_inicio_inscripcion;
		$this->fecha_fin_inscripcion = $fecha_fin_inscripcion;
		$this->fecha_inicio_ofertas = $fecha_inicio_ofertas;
		$this->fecha_fin_ofertas = $fecha_fin_ofertas;
        $this->excluir_subasta_id = $excluir_subasta_id;
	}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
     
        // Pido una sóla, no necesito traer todos los datos.
        $subastas = Subasta::listar(1, 1, [
            'estado' => 'Creada',
            'disponible_entre_fechas' => [
                'desde' => $this->fecha_inicio_inscripcion,
                'hasta' => $this->fecha_fin_ofertas,
            ],
        ]);
        
        foreach ($subastas as $subasta) {
        
            // Si la única que encuentra es la que se actualiza,
            // no tenerla en cuenta
            if ((int)$subasta->id === (int)$this->excluir_subasta_id) {
                continue;
            }
            
            // Cualquiera que colisione, es suficiente.
            return false;

		}

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ya existe una subasta para el período ingresado';
    }
}
