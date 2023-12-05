<?php

namespace App\Modules;

use App\Base\Module;
use App\Modules\Agencias\AgenciasModule;
use App\Modules\Auth\AuthModule;
use App\Modules\Base\BaseModule;
use App\Modules\Email\EmailModule;
use App\Modules\Direcciones\DireccionesModule;
use App\Modules\Financiacion\FinanciacionModule;
use App\Modules\Hubspot\HubspotModule;
use App\Modules\Onboarding\OnboardingModule;
use App\Modules\Parametros\ParametrosModule;
use App\Modules\Prendarios\PrendariosModule;
use App\Modules\Publicaciones\PublicacionesModule;
use App\Modules\Seguros\SegurosModule;
use App\Modules\Shared\SharedModule;
use App\Modules\Subastas\SubastasModule;
use App\Modules\Users\UsersModule;
use App\Modules\Vehiculos\VehiculosModule;

class Modules extends Module {
    
    public function boot() {
        parent::boot();
        $this->router()->prefix('api')->middleware('api')->group(function() {
            $this->bootAllRoutes('api');
        });
        
        $this->router()->middleware('web')->group(function() {
            $this->bootAllRoutes('web');
        });
    }
        
    public function register() {
		$this->provide(UsersModule::class			);
        $this->provide(PublicacionesModule::class	);
        $this->provide(ParametrosModule::class   	);
        $this->provide(VehiculosModule::class    	);
        $this->provide(AuthModule::class         	);
        $this->provide(OnboardingModule::class   	);
        $this->provide(SubastasModule::class        );
		$this->provide(PrendariosModule::class		);
		$this->provide(SharedModule::class		    );
		$this->provide(FinanciacionModule::class	);
		$this->provide(EmailModule::class           );
		$this->provide(DireccionesModule::class     );
        $this->provide(BaseModule::class            );
        $this->provide(AgenciasModule::class            );
        $this->provide(SegurosModule::class         );
        $this->provide(HubspotModule::class         );
    }
}
