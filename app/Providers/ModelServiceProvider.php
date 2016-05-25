<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ModelServiceProvider
 * @package App\Providers
 */
class ModelServiceProvider extends ServiceProvider
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
        foreach (\File::allFiles(base_path() . '/Core/Models') as $partial) {
            $this->app->bind("Core\\Models\\{$partial->getPathname()}");
        }
    }
}
