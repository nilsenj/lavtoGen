<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 6/25/2016
 * Time: 8:40 PM
 */

namespace Core\modular\Facades;


use Illuminate\Support\Facades\Facade;

class LikeCounter extends Facade
{
    protected static function getFacadeAccessor(){

        return 'likecounter';
    }
}