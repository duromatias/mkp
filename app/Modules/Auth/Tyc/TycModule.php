<?php

namespace App\Modules\Auth\Tyc;

use App\Base\Module;
use Illuminate\Support\Facades\Route;

class TycModule extends Module
{
	public static function defineHttpRoutes() {
		Route::prefix('tyc')->group(function() {
			Route::get('', [TycController::class, 'get']);
		});

		Route::prefix('admin/tyc')->middleware(['auth:api', 'admin'])->group(function() {
			Route::put('', [TycController::class, 'update']);
		});
	}
}