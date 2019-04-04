<?php

namespace NamTran\LaravelMakeRepositoryService;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(MakeRepository::class);
        $this->commands(MakeService::class);
    }
}
