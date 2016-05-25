<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class HelperServiceProvider
 * @package App\Providers
 */
class HelperServiceProvider extends ServiceProvider
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
        foreach (glob(base_path() . '/Helpers/*.php') as $filename) {
            require_once($filename);
        }
    }
}
