<?php

use App\Modules\Subastas\Emails\InicioOfertasMail;
use App\Modules\Subastas\Subasta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Modules\S1\Api;
use App\Modules\S1\ApiAgencias;
use GuzzleHttp\Exception\ClientException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/vehiculos', function (Request $request) {
    
    if (empty($request->session()->get('tokenInfo'))) {
        return redirect('/');
    }
    return view('vehiculos');
});


Route::post('/vehiculos', function(Request $request) {
    
    if (empty($request->session()->get('tokenInfo'))) {
        return response(json_encode([
            'error' => 'Falta identificarse',
        ], 422));
    }
    
    $file    = $request->file('foto');
    $mime    = $file->getMimeType();
    $content = $file->getContent();    
    $foto    = "data:{$mime};base64," . base64_encode($content);
    
    ApiAgencias::crearVehiculo($request->session()->get('tokenInfo')['access_token'], [
        'Desc'   => "RENAULT CLIO MIO 1.2 3 P CONFORT",
        'Domain' => "",
        'Gama'   => 1,
        'Km'     => "45000",
        'Marca'  => 36,
        'Modelo' => 360708,
        'Photos' => [
            [
                'Active'      => true,
                'Base64'      => $foto,
                'Orientation' => 'FRONT',
                'Path'        => null,
            ],
            [
                'Active'      => true,
                'Base64'      => $foto,
                'Orientation' => 'BACK',
                'Path'        => null,
            ],
            [
                'Active'      => true,
                'Base64'      => $foto,
                'Orientation' => 'LEFT',
                'Path'        => null,
            ],
            [
                'Active'      => true,
                'Base64'      => $foto,
                'Orientation' => 'RIGHT',
                'Path'        => null,
            ],
            [
                'Active'      => true,
                'Base64'      => $foto,
                'Orientation' => 'KM',
                'Path'        => null,
            ],
        ],
        "Price" => "775000",
        "Year"  => "2014",
    ]);
    
    
    return redirect('/vehiculos?mensaje=Vehiculo+agregado');
    
});

Route::post('/login', function(Request $request) {
    
    $http = Api::create();
    
    try {
        $response = $http->post('/oauth/token', [
            /*'headers' => [
                'Content-Type'    => 'application/json',
                'Accept-Encoding'    => 'gzip, deflate, br',
                'Accept'    => 'application/json',
                'Connection' => 'keep-alive',
                'User-Agent' => 'PostmanRuntime/7.26.8',
            ],*/
            'json' => [
                "grant_type"    => "password",
                "client_id"     => "4",
                "client_secret" => "F5bfk51B0wDJLDU0cko1r0I5vn1tl41Ieak9KRxE",
                "scope"         => "*",
                "username"      => $request->get('username'),
                "password"      => $request->get('password'),
            ],
        ]);
    } catch (ClientException $e) {
        if ($e->getResponse()->getStatusCode() != 200) {
            return redirect('/?mensaje=' . urlencode('Usuario y/o contraseña invalida'));
        } else {
            return redirect('/?mensaje=Error+inesperado');
        }
        $resp = json_decode($e->getResponse()->getBody()->getContents(), true);
        
    }
    
    $tokenInfo = json_decode($response->getBody()->getContents(), true);
    
    $response = $http->get('/api/account/profile', [
        'headers' => [
            "authorization" => "Bearer {$tokenInfo['access_token']}",
        ],
    ]);
            
    $userData = json_decode($response->getBody()->getContents(), true);
    //print_r($userData); die();
    if (empty($userData['data']['ready'])) {
        return redirect('/?mensaje=Usuari+no+S1');
    } 
    
    $request->session()->put('tokenInfo', $tokenInfo);
    return redirect('/vehiculos');
});