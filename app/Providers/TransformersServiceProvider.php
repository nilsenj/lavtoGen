<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TransformersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (\File::allFiles(base_path() . '/Core/Transformers') as $partial) {
            $this->app->bind("Core\\Transformers\\{$partial->getPathname()}");
        }
    }
}
