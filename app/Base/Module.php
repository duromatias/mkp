<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Base;

use App\Console\Kernel;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use ReflectionClass;

/**
 * Description of Module
 *
 * @author kodear
 */
abstract class Module extends ServiceProvider {
    
    protected $subModules = [];

    public $routePrefix;

    public $apiRoutesPrefix = '';
    public $webRoutesPrefix = '';
    
    public function boot() {
        $this->bootCommands();

        $this->bootViews();

        if (app()->runningInConsole()) {
            $migrationsPath = $this->getMigrationsPath();
            if (is_dir($migrationsPath)) {
                $this->loadMigrationsFrom([$this->getMigrationsPath()]);
            }
        }
    }
    
    /**
     * Registra un submódulo y lo agrega a la lista
     */
    protected function provide(string $moduleClass) {
        $this->subModules[] = $this->app->register($moduleClass);
    }
    
    public function getRoutePrefixForCurrent(): string {
    	if ($this->routePrefix) {
    		return $this->routePrefix;
		}

        $baseName = (new \ReflectionClass($this))->getShortName();
        if (substr($baseName, -6) === 'Module') {
            return strtolower(substr($baseName, 0, -6));
        }
        
        return strtolower($baseName);
    }

    protected function getModuleRootPath(): string {
        $ruta = str_replace('App\\', '', get_class($this));
        $ruta = str_replace('\\', DIRECTORY_SEPARATOR, $ruta);
        $ruta = app_path($ruta);
        $ruta = dirname($ruta);
        return $ruta;
    }
    
    protected function getMigrationsPath() {
        return $this->getModuleRootPath() . DIRECTORY_SEPARATOR . 'Migrations';
    }
    
    /**
     * Rutas de api para el módulo
     */
    public function bootApiRoutes() { 
    
    }
    
    /**
     * Rutas web para el módulo
     */
    public function bootWebRoutes() { 
    
    }
    
    /**
     * Registra las rutas del módulo y sus submódulos
     * de forma recursiva.
     * 
     */
    final public function bootAllRoutes($type = 'api') {
        foreach($this->subModules as $module) {
            
            $customPrefix = $module->getRoutePrefix($type);
            $modulePrefix = $module->getRoutePrefixForCurrent();
            $prefix       = $customPrefix . $modulePrefix;
                    
            $this->router()
                ->prefix($prefix)
                ->group(function() use($module, $type) {
                    if ($type === 'api') {
                        $module->bootApiRoutes();
                    }
                    if ($type === 'web') {
                        $module->bootWebRoutes();
                    }
                    $module->bootAllRoutes($type);
                }
            );
        }
    }
    
    public function bootSchedule() {
    }
    
    private function getRoutePrefix($type): string {
        $prefix = '';
        if ($type === 'api') {
            $prefix = $this->apiRoutesPrefix;
        }
        if ($type === 'web') {
            $prefix = $this->webRoutesPrefix;
        }
        $result = $prefix ? $prefix . '/' : '';
        
        return $result;
    }
    
    protected function bootCommands() {
		$reflectedClass = new ReflectionClass($this);

		$commandsPath = dirname($reflectedClass->getFileName()) . '/Commands';

		if (is_dir($commandsPath)) {
			app()->make(Kernel::class)->loadCommands($commandsPath);
		}
        
        $this->onSchedule(function() {
            $this->bootSchedule();
        });
    }
    
    public function router(): Router {
        return $this->app->make(Router::class);
    }
    
    public function console(): Kernel {
        return $this->app->make(Kernel::class);
    }
    
    public function scheduler(): Schedule {
        return $this->app->make(Schedule::class);
    }
    
    public function onRouter(callable $function) {
        $this->callAfterResolving(Router::class, $function);
    }
    
    public function onSchedule(callable $function) {
        
        $this->callAfterResolving(Schedule::class, function(Schedule $schedule) use ($function) {
            $function($schedule);
        });
    }

    public function bootViews() {
		$reflectedClass = new ReflectionClass($this);

		$viewsPath = dirname($reflectedClass->getFileName()) . '/Views';

		if (is_dir($viewsPath)) {
			View::getFinder()->prependLocation(
				$viewsPath
			);
		}
	}
    
    static public function defineHttpRoutes() {
        
    }
}
