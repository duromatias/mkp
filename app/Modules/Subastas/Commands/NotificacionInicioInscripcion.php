<?php

namespace App\Modules\Subastas\Commands;

use App\Base\Repository\RepositoryException;
use App\Modules\Subastas\Business\SubastasBusiness;
use App\Modules\Subastas\Subasta;
use Illuminate\Console\Command;

class NotificacionInicioInscripcion extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'subastas:notificar-inicio-inscripcion';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Verifica si la fecha de inicio de inscripción de alguna subasta coincide con la fecha actual 
    y notifica via email a los usuario agencia';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
	public function handle()
	{
		try {
			$subasta = Subasta::getOne([
				'estado' => Subasta::ESTADO_CREADA,
				'fecha_inicio_inscripcion' => date('Y-m-d')
			]);

			SubastasBusiness::notificarInicioInscripcion($subasta);
		} catch (RepositoryException $exception) {
			// No hay subasta con fecha inicio de ofertas en el día de la fecha
		}
	}
}
