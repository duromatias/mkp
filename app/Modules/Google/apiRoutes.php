<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/places')->group(__DIR__ . '/Places/apiRoutes.php');
