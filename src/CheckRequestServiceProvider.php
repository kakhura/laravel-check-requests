<?php

namespace Kakhura\CheckRequest;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Kakhura\CheckRequest\Console\Commands\RunCommands;
use Kakhura\CheckRequest\Http\Middleware\AdminMiddleware;
use Kakhura\CheckRequest\Http\Middleware\WithRequestIdentifier;

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
        $this->publishes([$configPath => config_path('kakhura.check-requests.php')], 'kakhura-site-bases-config');
    }

    protected function publishViews()
    {
        $this->publishAdminViews();
        if (config('kakhura.check-requests.publish_website_views')) {
            $this->publishWebsiteViews();
        }
        $this->publishOtherViews();
    }

    protected function publishAdminViews()
    {
        foreach (config('kakhura.check-requests.modules_publish_mapper') as $module) {
            $viewPath = __DIR__ . sprintf('/../resources/views/admin/%s', $module);
            if (File::exists($viewPath)) {
                $this->loadViewsFrom($viewPath, 'site-bases');
                $this->publishes([
                    $viewPath => base_path(sprintf('resources/views/vendor/site-bases/admin/%s', $module)),
                ], 'kakhura-site-bases-views');
            }
        }
    }

    protected function publishWebsiteViews()
    {
        foreach (config('kakhura.check-requests.modules_publish_mapper') as $module) {
            $viewPath = __DIR__ . sprintf('/../resources/views/website/client/%s', $module);
            if (File::exists($viewPath)) {
                $this->loadViewsFrom($viewPath, 'site-bases');
                $this->publishes([
                    $viewPath => base_path(sprintf('resources/views/vendor/site-bases/website/%s', $module)),
                ], 'kakhura-site-bases-views');
            }
        }
        $viewPath = __DIR__ . '/../resources/views/website/client/main';
        if (File::exists($viewPath)) {
            $this->loadViewsFrom($viewPath, 'site-bases');
            $this->publishes([
                $viewPath => base_path('resources/views/vendor/site-bases/website/main'),
            ], 'kakhura-site-bases-views');
        }
        $viewPath = __DIR__ . '/../resources/views/website/layouts';
        if (File::exists($viewPath)) {
            $this->loadViewsFrom($viewPath, 'site-bases');
            $this->publishes([
                $viewPath => base_path(sprintf('resources/views/vendor/site-bases/website/layouts', $module)),
            ], 'kakhura-site-bases-views');
        }
    }

    protected function publishOtherViews()
    {
        $viewPath = __DIR__ . '/../resources/views/translation-manager';
        if (File::exists($viewPath)) {
            $this->loadViewsFrom($viewPath, 'site-bases');
            $this->publishes([
                $viewPath => base_path('resources/views/vendor/translation-manager'),
            ], 'kakhura-site-bases-views');
        }
        $viewPath = __DIR__ . '/../resources/views/admin/inc';
        if (File::exists($viewPath)) {
            $this->loadViewsFrom($viewPath, 'site-bases');
            $this->publishes([
                $viewPath => base_path('resources/views/vendor/site-bases/admin/inc'),
            ], 'kakhura-site-bases-views');
        }
        $viewPath = __DIR__ . '/../resources/views/admin/index.blade.php';
        if (File::exists($viewPath)) {
            $this->loadViewsFrom($viewPath, 'site-bases');
            $this->publishes([
                $viewPath => base_path('resources/views/vendor/site-bases/admin/index.blade.php'),
            ], 'kakhura-site-bases-views');
        }
    }

    protected function publishMigrations()
    {
        foreach (config('kakhura.check-requests.modules_publish_mapper') as $module) {
            $migrationPath = __DIR__ . sprintf('/../database/migrations/%s', $module);
            if (File::exists($migrationPath)) {
                $this->publishes([
                    $migrationPath => base_path('database/migrations'),
                ], 'kakhura-site-bases-migrations');
            }
        }
    }
}
