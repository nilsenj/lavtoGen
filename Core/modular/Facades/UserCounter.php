<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 6/25/2016
 * Time: 8:42 PM
 */

namespace Core\modular\Facades;


use Illuminate\Support\Facades\Facade;

class UserCounter extends Facade
{
    protected static function getFacadeAccessor(){

        return 'usercounter';
    }
}