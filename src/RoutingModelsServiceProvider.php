<?php


namespace SimpelDigitaal\RoutingModels;

use function compact;
use Illuminate\Support\ServiceProvider;

class RoutingModelsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/routingmodels.php' => config_path('routingmodels.php'),
        ]);

        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/routingmodels'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->loadViewsFrom(__DIR__.'/views', 'courier');


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
        $records = RoutingRecord::all();
        $router = $this->app['router'];

        foreach($records as $record) {
            $route = $router->addRoute($record->getMethods(), $record->slug, $record->getAction());
            $route->defaults('record', $record);
        }

    }

}