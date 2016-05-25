<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class PresentersServiceProvider
 * @package App\Providers
 */
class PresentersServiceProvider extends ServiceProvider
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
        foreach (\File::allFiles(base_path() . '/Core/Presenters') as $partial) {
            $this->app->bind("Core\\Presenters\\{$partial->getPathname()}");
        }
    }
}
