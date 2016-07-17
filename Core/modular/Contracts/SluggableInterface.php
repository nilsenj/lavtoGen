<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 6/19/2016
 * Time: 8:46 PM
 */

namespace Core\modular\Contracts;


interface SluggableInterface
{
    public function getSlug();

    public function sluggify($force = false);

    public function resluggify();

}