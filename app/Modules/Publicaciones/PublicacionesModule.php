<?php

namespace App\Modules\Publicaciones;

use App\Base\Module;
use App\Modules\Publicaciones\Consultas\ConsultasModule;
use App\Modules\Publicaciones\Favoritos\FavoritosModule;
use App\Modules\Publicaciones\Home\HomeModule;
use App\Modules\Publicaciones\MisPublicaciones\MisPublicacionesModule;
use App\Modules\Publicaciones\Multimedia\MultimediaModule;
use App\Modules\Publicaciones\Ofertas\OfertasModule;
use App\Modules\Publicaciones\Subastas\SubastasModule;
use Illuminate\Support\Facades\Route;

class PublicacionesModule extends Module {
    
    public static function defineHttpRoutes() {
        Route::prefix('/')->group(function() { HomeModule::defineHttpRoutes(); });
        //Route::prefix('/mis-publicaciones')->group(function() { MisPublicacionesModule::defineHttpRoutes(); });
        Route::prefix('/')->group(function() { MisPublicacionesModule::defineHttpRoutes(); });

        Route::prefix('/*')->group(function() {
        	ConsultasModule::defineHttpRoutes();
		});

        Route::prefix('/*/subastas')->group(function() {
        	SubastasModule::defineHttpRoutes();
		});

        Route::prefix('{publicacion_id}')->group(function() {
        	OfertasModule::defineHttpRoutes();
        	FavoritosModule::defineHttpRoutes();
		});
    }
    
    public function register() {
        $this->app->register(HomeModule            ::class);
        $this->app->register(MultimediaModule      ::class);
        $this->app->register(MisPublicacionesModule::class);
        $this->app->register(ConsultasModule       ::class);
        $this->app->register(OfertasModule         ::class);
        $this->app->register(FavoritosModule       ::class);
        $this->app->register(SubastasModule        ::class);
    }
}
