<?php

namespace Kakhura\LaravelCheckRequest;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Kakhura\LaravelCheckRequest\Http\Middleware\WithRequestIdentifier;

class CheckRequestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('with_request_identifier', [WithRequestIdentifier::class]);
        $this->publishConfigs();
        $this->publishViews();
        $this->publishMigrations();
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    protected function publishConfigs()
    {
        $configPath = __DIR__ . '/../config/kakhura.check-requests.php';
        $this->mergeConfigFrom($configPath, 'kakhura.check-requests');
        $this->publishes([$configPath => config_path('kakhura.check-requests.php')], 'kakhura-check-requests-config');
    }

    protected function publishViews()
    {
        $viewPath = __DIR__ . '/../resources/lang/en/messages.php';
        if (File::exists($viewPath)) {
            $this->loadViewsFrom($viewPath, 'check-requests');
            $this->publishes([
                $viewPath => base_path('resources/lang/vendor/check-requests/en'),
            ], 'kakhura-check-requests-views');
        }
    }

    protected function publishMigrations()
    {
        $migrationPath = __DIR__ . '/../database/migrations';
        if (File::exists($migrationPath)) {
            $this->publishes([
                $migrationPath => base_path('database/migrations'),
            ], 'kakhura-check-requests-migrations');
        }
    }
}
