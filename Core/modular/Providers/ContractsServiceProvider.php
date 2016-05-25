<?php

namespace Core\Modular\Providers;

use Illuminate\Support\ServiceProvider;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(
            'Core\Modular\Contracts\RepositoryInterface',
            'Core\Modular\Repository'
        );
    }
}
