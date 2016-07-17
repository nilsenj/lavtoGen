<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 6/19/2016
 * Time: 9:59 PM
 */

namespace Core\modular\Models;


use Illuminate\Database\Eloquent\Model;
use RepositoryLab\Repository\Contracts\Transformable;
use RepositoryLab\Repository\Traits\TransformableTrait;

class Counter extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'modular_counter';

    protected $fillable = array('class_name', 'object_id', 'view_counter');
}
