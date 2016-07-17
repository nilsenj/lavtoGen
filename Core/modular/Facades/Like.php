<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 6/25/2016
 * Time: 8:39 PM
 */

namespace Core\modular\Facades;


use Illuminate\Support\Facades\Facade;

class Like extends Facade
{
    protected static function getFacadeAccessor(){

        return 'like';
    }
}