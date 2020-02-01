<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Generator Config
    |--------------------------------------------------------------------------
    |
    */
    'generator' => [
        'basePath' => app()->path(),
        'rootNamespace' => 'App\\',
        'stubsOverridePath' => app()->path(),
        'paths' => [
            'repositories' => 'Repositories',
            'repository_interfaces' => 'Repositories',
            'services' => 'Services',
            'service_interfaces' => 'Services',
            'repository_provider' => 'RepositoryServiceProvider',
            'service_provider' => 'BootstrapServiceProvider',
        ]
    ]
];