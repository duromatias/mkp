<?php

namespace App\Modules\Financiacion\Modules\Solicitud\Commands;

use App\Modules\Financiacion\Modules\Solicitud\SolicitudBusiness;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Users\Models\User;
use Illuminate\Console\Command;

class TestObtenerPlazosPorPublicacionId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'financiacion:test-obtener-plazos-por-publicacion-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     *
     * @return int
     */
    public function handle()
    {
        
//        $data = json_encode([
//            "brands" => 19,
//            "models" => 190171,
//            "years" => "2020-6131000",
//            "products" => "4110",
//            "use" => 1,
//            "capital" => 2000000,
//            "maxFinVip" => 3600000,
//            "postal_codes" => "2000",
//            "provinces" => "21",
//            "streets" => "ROSARIO (Santa Fe)",
//            "_agency_id" => 40416066,
//            "salary" => 0,
//            "retorno" => 0,
//            "ceroKm" => false,
//        ]);
//        $ret = shell_exec(implode(' ', [
//            "curl -s 'https://s1.decreditoslabs.com/simulador/getPlazos'",
//            "-H 'Accept: application/json, text/plain, */*'",
//            "-H 'Content-Type: application/json;charset=UTF-8'",
//            "-H 'Cookie: _ga_QNGNXPFXNE=GS1.1.1658752090.15.1.1658753118.0; _ga=GA1.2.2060762877.1658149170; _gid=GA1.2.1875135684.1658754761; cebs=1; _ce.s=v~5a4c8c053a7be9b5e4ef9de0a66f9cd052bda4c9~vpv~2; laravel_session=eyJpdiI6InlwYUNnQ2hUUVR3eDg1MFJjZWNKSVE9PSIsInZhbHVlIjoiNXhWNTJBVGZlUDdlQ3FmczJWZ1Y3Z1pwbmR1XC81a3NtblNMMmdidytHRXUyRm1Kb2FPXC9EUVZUODAwa0tFTTk2IiwibWFjIjoiMGQ4ZWU4ZmM0MTlkOWEyNzgyMTQ1YWU4ZDEwNWY1YjBlM2U5ZjZhZmZjNmRlM2EyMzI0ZTc1YzI1ZDI3NmVlYyJ9'",
//            "-H 'Host: s1.decreditoslabs.com'",
//            "--data-raw '{$data}'",
//            "--compressed"
//        ]));
//            
//        print_r($ret);
//        die();
        
        /*$accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiMjE0NTM5ZDM3MjhjOGNjZDRlMGEzNTcyYWJjODQ4YTAxOWY3ODQ5NDRhZThhNDA2ZWJkMWY2OWY1YWUyNTE2Mjc1YTg5OWM3NmZiNDVlZDIiLCJpYXQiOjE2NTg3NTMwMDUuMjQwNDEsIm5iZiI6MTY1ODc1MzAwNS4yNDA0MTQsImV4cCI6MTY1ODgxNzgwNS4xOTgzMiwic3ViIjoiNjIzMiIsInNjb3BlcyI6WyIqIl19.YyVUX-X2X-Gbocg2JDijsV6z9AZhaH_wpz_FZ5pO-mo-WV2BsbMnjJc_kM86OlRDNG5noqnVgnhNGk9VmixBGm9otEjB2Kv12TDsEdHAPKDPg-tjC1BVU3qIx5wl4siPvryHXGWIP449RFDE9HH5Bte68FFz3nbNU6uCaj61Wf8fdpH4nmic4RS2WtRx5npjepgpdgzZFtOZR2CRPRPShzI3-8WOckoQcqot-rm87d5gvVfDwiVMv724_LgFslMeJhQLB1J0QGYVai0KGmW6mx3PTZ0Z_quZ0YYtsAE-Ld71SMXHtbromod_rUkEPzFZPi_Oh1gVMgRvV9b0WeBeoRYu9n2BhvnJBqEj9yPHAsFQqGvU-3IEAWYq83PcKThaylW7ocBpKRS8HmpuyeInXj5d6afVQSKX6RROU_zCI0d-sT7fLs4NBS_eRUUsVHv7ikDYt0QwNdUBh6rALv6EE5ESbKNJGQNsUVtDflp5TFAhf1xBgcXrUS2CmFu0-Cc-EWPVbr9zUkIs8297Ttzrn9atPkvF0t4S-3olAV9jDE8W3ECE6TSCinPVseUP5z9ecgIZnAa76q_0CnLqybuSqG4vRkFbyzpI2romSeo67vLvzHpiL1Wlhyk8Zd84I821Qk2wOoAbyFQlc7BlsA6hPkHG-eUqFn6O4TK5E9o-3_U';
        $client = new \App\Base\CurlClient(config('prendarios.base_url'));
        $respuesta = $client->get("/agencias/mis-unidades?access_token={$accessToken}");
        
        $respuesta = $client->post('/simulador/getPlazos', [
            "brands" => 19,
            "models" => 190171,
            "years" => "2020-6131000",
            "products" => "4110",
            "use" => 1,
            "capital" => 2000000,
            "maxFinVip" => 3600000,
            "postal_codes" => "2000",
            "provinces" => "21",
            "streets" => "ROSARIO (Santa Fe)",
            "_agency_id" => 40416066,
            "salary" => 0,
            "retorno" => 0,
            "ceroKm" => false,
        ]);
        print_r($respuesta);
        
        die();*/
        
        $solicitante = User::getByEmail('scordova2@kodear.net');
        $publicacion = Publicacion::getById(328);
        $capital     = 2000000;
        $business    = (new SolicitudBusiness($solicitante, $publicacion)); 
        $data = $business->obtenerCuotasPorUsuario($capital);
        // $data = $business->obtenerDatos(239455);
        
        print_r($data);
        
        return 0;
    }
}
