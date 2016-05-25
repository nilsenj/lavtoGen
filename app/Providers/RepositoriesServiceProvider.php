<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoriesServiceProvider
 * @package App\Providers
 */
class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (\File::allFiles(base_path() . '/Core/RepositoryInterfaces') as $partial) {
            $this->app->bind(
                "Core\\RepositoryInterfaces\\{$partial->getPathname()}",
                "Core\\Repositories\\{$partial->getPathname()}Eloquent");
        }
    }
}
