<?php


namespace SimpelDigitaal\RoutingModels;

use function compact;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;

class RoutingModelsServiceProvider extends ServiceProvider
{

    const VIEW_NAME_SPACE = 'routingrecords';

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/routingmodels.php' => config_path('routingmodels.php'),
        ]);


        $this->loadMigrationsFrom(__DIR__.'/migrations');


        $namespace = self::VIEW_NAME_SPACE;
        $this->loadViewsFrom(__DIR__.'/views', $namespace);


        if ($this->app->runningInConsole()) {
            $this->commands([
            ]);
        }

        $this->routeModels();

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/routingmodels.php', 'routingmodels'
        );

    }

    private function routeModels()
    {
        if ($this->app->routesAreCached()) {
            // Hopefully these routes are cached too...
        } else {
            $this->loadRoutesForModels(compact('record'));

            $this->app->booted(function () {
                $this->app['router']->getRoutes()->refreshNameLookups();
                $this->app['router']->getRoutes()->refreshActionLookups();
            });
        }
    }

    private function loadRoutesForModels($compact)
    {
        try {
            $records = RoutingRecord::all();
        } catch (QueryException $e) {
            // Probably the migration has not been submitted, so we will omit this exception for now.
            $records = [];
        }

        $router = $this->app['router'];

        foreach($records as $record) {
            $route = $router->addRoute($record->getMethods(), $record->slug, $record->getAction());
            $route->defaults('record', $record);
        }



    }

}