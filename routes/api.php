<?php

use App\Modules\Modules;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/auth')->group(app_path('Modules/Auth/apiRoutes.php'));
Route::prefix('/google')->group(app_path('Modules/Google/apiRoutes.php'));
Route::prefix('/users')->group(app_path('Modules/Users/apiRoutes.php'));
Route::prefix('/publicaciones')->group(app_path('Modules/Publicaciones/apiRoutes.php'));

Modules::defineHttpRoutes();
