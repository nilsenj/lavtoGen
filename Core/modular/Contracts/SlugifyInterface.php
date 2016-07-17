<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 6/19/2016
 * Time: 8:53 PM
 */

namespace Core\modular\Contracts;


interface SlugifyInterface
{
    /**
     * Return a URL safe version of a string.
     *
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    public function slugify($string, $separator = '-');
}