<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\ServiceProvider;
use SXC\Models\GroupLicenseUsers;
use SXC\Models\Product;
use SXC\Models\SingleLicenseUsers;

class CustomValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('old_password', function($attribute, $value, $parameters, $validator) {

            $user = User::findOrFail($parameters[0]);
            return \Hash::check($value, $user->password);

        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
