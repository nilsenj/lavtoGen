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
        \Validator::extend('version_exists', function($attribute, $value, $parameters, $validator) {

            $product = Product::findOrFail($parameters[1]);
            $versions = $product->getProductDescrptionByVersion($parameters[0]);

            $countVersions = $versions->count();
            if($countVersions >= 1){
                return false;
            } else return true;
        });

        \Validator::extend('email_exists_license', function($attribute, $value, $parameters, $validator) {

            $type = $parameters[1];
            $license_id = $parameters[0];
            $email = $value;

            if($type == "group") {
                $licenseUser = GroupLicenseUsers::licensed($license_id)->email(trim($email));

                $countLicenseUserWithEmail = $licenseUser->count();
                if($countLicenseUserWithEmail >= 1){
                    return false;
                } else return true;
            }

            if($type == "single"){
                $licenseUser =SingleLicenseUsers::licensed($license_id)->email(trim($email));

                $countLicenseUserWithEmail = $licenseUser->count();
                if($countLicenseUserWithEmail >= 1){
                    return false;
                } else return true;
            }
            return false;
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
