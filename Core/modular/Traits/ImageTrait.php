<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11/12/2015
 * Time: 8:16 AM
 */

namespace Core\modular;

use Image;
use League\Flysystem\Exception;
use Core\modular\Models\ModuleLayer;

/**
 * Class ImageTrait
 * @package SyrinxCore\Uploader
 */
trait ImageTrait
{

    /**
     * bind an image to the model
     *
     * @param $image
     * @param $instance
     */
    public function bindImage($image, $instance)
    {

        if ($instance->getMedia('images')->count() !== 0) {

            $instance->clearMediaCollection('images');
        }
//        $image = $this->resizeImage($image);
        $instance->addMedia($image)->toCollection('images');

    }

    /**
     * @param $images
     * @param $instance
     */
    public function bindImageS($images, $instance)
    {
        foreach ($images as $image) {
            if ($instance->getMedia('images')->count() !== 0) {

                $instance->clearMediaCollection('images');
            }
//            $image = $this->resizeImage($image);
            $instance->addMedia($image)->toCollection('images');
        }
    }

    /**
     * bindLogoImage
     * @param $image
     * @param $instance
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindLogoImage($image, $instance)
    {
        try {

            if ($instance->getMedia('logo')->count() !== 0) {

                $instance->clearMediaCollection('logo');
            }
//            $image = $this->makeLogo($image);

            $instance->addMedia($image)->toCollection('logo');

        } catch (Exception $e) {
            return response()->json('error', $e->getCode());
        }
    }

    /**
     * @param $instance
     * @return \Illuminate\Http\JsonResponse
     */
    public function unbindLogoImage($instance)
    {
        try {

            if ($instance->getMedia('logo')->count() !== 0) {

                $instance->clearMediaCollection('logo');
            }

        } catch (Exception $e) {
            return response()->json('error', $e->getCode());
        }
    }


    /**
     * bind logo from url
     *
     * @param $url
     * @param $instance
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindLogoFromURL($url, $instance)
    {
        try {

            if ($instance->getMedia('logo')->count() !== 0) {

                $instance->clearMediaCollection('logo');
            }
            $instance->addMediaFromUrl($url)
                ->toCollection('logo');

        } catch (Exception $e) {

            return response()->json('error', $e->getCode());

        }
    }

    /**
     * bind image from url
     * @param $url
     * @param $instance
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindImageFromURL($url, $instance)
    {
        try {

            if ($instance->getMedia('images')->count() !== 0) {

                $instance->clearMediaCollection('images');
            }
            $instance->addMediaFromUrl($url)
                ->toCollection('images');

        } catch (Exception $e) {

            return response()->json('error', $e->getCode());

        }
    }

    /**
     * bind multiple images from array of urls
     * @param array $urls
     * @param $instance
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindImageSFromURL(array $urls, $instance)
    {
        try {


            if ($instance->getMedia('images')->count() !== 0) {

                $instance->clearMediaCollection('images');
            }
            foreach ($urls as $url) {
                $instance->addMediaFromUrl($url)
                    ->toCollection('images');
            }
        } catch (Exception $e) {

            return response()->json('error', $e->getCode());

        }
    }

    /**
     * get image
     * @return mixed
     */
    public function getImage()
    {
        $pictures = $this->getMedia('images');
        $picture = $pictures[0]->getUrl();
        return $picture;
    }

    /**
     * get logo image
     * @return mixed
     */
    public function getLogo()
    {
        $pictures = $this->getMedia('logo');
        $picture = $pictures[0]->getUrl();
        return $picture;
    }

    /**
     *
     */
    public function registerMediaConversions()
    {
        // Perform a resize and filter on images from the images- and anotherCollection collections
        // and save them as png files.
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 368, 'h' => 232, 'filt' => 'greyscale', 'fm' => 'png'])
            ->performOnCollections('images', 'anotherCollection')
            ->nonQueued();

        // Perform a resize and sharpen on every collection
        $this->addMediaConversion('adminThumb')
            ->setManipulations(['w' => 50, 'h' => 50, 'sharp' => 15])
            ->performOnCollections('*');

        // Perform a resize on every collection
        $this->addMediaConversion('big')
            ->setManipulations(['w' => 500, 'h' => 500]);
    }

    /**
     * @param $file
     * @return array
     */
    private function thumbImage($file)
    {
        $height = Image::make($file)->height();
        $width = Image::make($file)->width();
        $aspect = $height / $width;
        $image = [];
        if ($aspect > 1) {
            $image->width = 450 / $aspect;
            $image->height = 450;
        } else if ($aspect < 1) {
            $image->width = 450;
            $image->height = 450 * $aspect;
        } else {
            $image->width = 450;
            $image->height = 450;
        }
        return $image;
    }

    /**
     * @param $file
     * @return \Intervention\Image\Image
     */
    private function makeLogo($file)
    {

        $image = Image::make($file)->fit(150);
        return $image;
    }

    /**
     * @param $folder
     * @param $img
     * @return string
     */
    private function resolveDefaultImgPath($folder, $img) {

        $path = $folder . '/' . $img->filename;
        if (\Storage::exists($path)) {

            \Storage::delete($path);
        } else {
            \Storage::put($path, $img);
        }
        return $path;
    }

    /**
     * @param $img
     * @param $user
     * @return string
     */
    private function userLogoImage($img, $user)
    {
        $folder = 'users/'.$user->id;
        return $this->resolveDefaultImgPath($folder, $img);
    }

    /**
     * @param $img
     * @param $module
     * @return string
     */
    private function moduleLogoImage($img, $module)
    {
        $folder = 'module/'.$module->id;
        return $this->resolveDefaultImgPath($folder, $img);
    }

    /**
     * @param $img
     * @param $user
     * @return string
     */
    private function updateUserLogoImage($img, $user)
    {
        $folder = 'users/'.$user->id;
        return $this->resolveDefaultImgPath($folder, $img);
    }

    /**
     * @param $user
     */
    public function makeDefaultUserImg($user)
    {

        $img = \DefaultProfileImage::create($user->name, 256, '#eee', '#34495e');
        $img->filename = $user->id . '-' . str_random(8) . '.png';
        $img->extension = 'png';
        $imgUser = $img->encode();
        $logo = $user->userLogoImage($imgUser, $user);
        $user->logo_path = $logo;
        $user->save();
    }

    /**
     * @param ModuleLayer $module
     */
    public function makeDefaultModuleImg($module)
    {

        $img = \DefaultProfileImage::create($module->name, 512, '#eee', '#34495e');
        $img->filename = $module->id . '-' . str_random(8) . '.png';
        $img->extension = 'png';
        $imgModule = $img->encode();
        $logo = $module->moduleLogoImage($imgModule, $module);
        $module->logo_path = $logo;
        $module->save();
    }
}
