<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 6/19/2016
 * Time: 8:44 PM
 */

namespace Core\modular\Models;

use Carbon\Carbon;
use Core\modular\Contracts\SluggableInterface;
use Core\modular\Traits\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use RepositoryLab\Repository\Contracts\Transformable;
use RepositoryLab\Repository\Traits\TransformableTrait;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Core\modular\Contracts\Likeable;
use Core\modular\Traits\ViewCounterTrait;
use Core\modular\ImageTrait;
use Storage;

/**
 * Class ModuleLayer
 * @package Syrinx\Models
 */
class ModuleLayer extends Model implements Transformable, SluggableInterface, Likeable, HasMedia
{
    use TransformableTrait;
    use \Core\modular\Traits\LikeableTrait;
    use HasMediaTrait;
    use ViewCounterTrait;
    use ImageTrait;
    use SluggableTrait;
    /**
     * @var string
     */
    protected $table = 'modules';
    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'created', 'date', 'default', 'private', 'status', 'thread_id', 'logo_path'];

    /**
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];
    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'date'];
    /**
     * @var string
     */
    /**
     * @param $date
     */
    public function setDateAttribute($date)
    {

        $this->attributes['date'] = Carbon::today();

    }

    /**
     * @param $query
     * @param $slugOrId
     * @return mixed
     */
    public function scopeBySlugOrId($query, $slugOrId)
    {
        return $query->where($slugOrId)->orWhere('slug', '=', $slugOrId);
    }

    /**
     * @param $query
     * @param $status
     * @return mixed
     */
    public function scopeActive($query, $status)
    {

        return $query->where('status', '=', $status);
    }

    /**
     * @param $query
     * @param $default
     * @return mixed
     */
    public function scopeDefault($query, $default)
    {

        return $query->where('default', '=', $default);
    }

    /**
     * @param $query
     * @param $private
     * @return mixed
     */
    public function scopePrivate($query, $private)
    {

        return $query->where('private', '=', $private);
    }

    /**
     * @param $query
     * @param $status
     * @return mixed
     */
    public function scopeIsActive($query, $status)
    {

        return $query->where('status', '=', $status);
    }

    /**
     * @return mixed
     */
    public function getLogoFromPath()
    {

        return Storage::get($this->logo_path);
    }

    /**
     * @return bool
     */
    public function haveGenericLogo()
    {

        return !is_null($this->logo_path);
    }

    /**
     * @return string
     */
    public function getLogoImg()
    {
        if (!empty($this->getMedia('logo')->first())) {
            $pictures = $this->getMedia('logo');
            $picture = $pictures[0]->getUrl();
            return url($picture);
        } elseif (empty($this->getMedia('logo')->first()) && $this->haveGenericLogo()) {
            return "data:image/jpeg;base64," . base64_encode($this->getLogoFromPath());
        } else {
            return url('images/default-module.png');
        }
    }

}