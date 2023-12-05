<?php

namespace App\Modules\Google\Places;

use Illuminate\Support\Facades\Route;


Route::get('/buscar',                     [ PlacesController::class, 'buscar'          ]);
Route::get('/obtenerDetalles/{place_id}', [ PlacesController::class, 'obtenerDetalles' ]);


