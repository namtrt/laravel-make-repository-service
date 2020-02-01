<?php

namespace NamTran\LaravelMakeRepositoryService;

use Illuminate\Support\ServiceProvider;
use NamTran\LaravelMakeRepositoryService\Generators\Commands\RepositoryCommand;
use NamTran\LaravelMakeRepositoryService\Generators\Commands\ServiceCommand;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(RepositoryCommand::class);
        $this->commands(ServiceCommand::class);
        if (class_exists('\\App\\Providers\\' . config('repository.generator.paths.repository_provider', 'RepositoryServiceProvider'))) {
            $this->app->register('\\App\\Providers\\' . config('repository.generator.paths.repository_provider', 'RepositoryServiceProvider'));
        }

        if (class_exists('\\App\\Providers\\' . config('repository.generator.paths.service_provider', 'BootstrapServiceProvider'))) {
            $this->app->register('\\App\\Providers\\' . config('repository.generator.paths.service_provider', 'BootstrapServiceProvider'));
        }
    }

    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/repository.php' => config_path('repository.php')
        ]);

        $this->mergeConfigFrom(__DIR__ . '/config/repository.php', 'repository');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
