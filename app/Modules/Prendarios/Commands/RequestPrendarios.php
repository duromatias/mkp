<?php

namespace App\Modules\Prendarios\Commands;

use App\Modules\Prendarios\Clients\PrendariosClient;
use Illuminate\Console\Command;

class RequestPrendarios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
	 * Route Example: "agencias/lite/simulador/getModelsByYear?query=honda&year=2017"
     */
    protected $signature = 'prendarios:request {route}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Request Prendarios';


	protected PrendariosClient $prendariosClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PrendariosClient $prendariosClient)
    {
        parent::__construct();
        $this->prendariosClient = $prendariosClient;
    }


    public function handle()
    {
		$route = parse_url($this->argument('route'));

		$path = $route['path'];

		if (array_key_exists('query', $route)) {
			parse_str($route['query'], $queryParameters);
		}
		else {
			$queryParameters = [];
		}


		/** @var string $response */
		$response = $this->prendariosClient->get($path, $queryParameters);

		print_r($response);
    }
}
