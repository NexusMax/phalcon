<?php
namespace App\Models;

use App\Models\Model;
use Phalcon\Image\Adapter\Imagick;
use Phalcon\Di;
/**
 * Vokuro\Models\Users
 * All the users registered in the application
 */
class Images extends Model
{


    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {

    }

    public function beforeCreate()
    {   
        
    }

    public function beforeSave()
    {

    }

    public function beforeUpdate()
    {
       
    }

    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {

    }

    /**
     * Validate that emails are unique across users
     */
    public function validation()
    {

    }

    public function initialize()
    {


    }


    public static function thumb($image, $width = null, $heigh = null, $quality = 80)
    {
        $di = Di::getDefault();

        $module = $di->get('router')->getModuleName();
        $module = 'frontend';
        $moduleAssetsDir = $module . 'AssetsDir';
        $moduleAssets = $module . 'Assets';

        $config = $di->get('config')->application;
        $dir = $config->$moduleAssetsDir;
        $public = $config->$moduleAssets;

        $data = explode('.', $image);
        $img = $data[0];
        $ext = $data[1];

        $name = $img . '_' . $width . 'x' . $heigh . '.' . $ext;

        if(!is_file($dir . $name) && is_file($dir . $image)){
           
            $logo = new Imagick($dir . $image);
            $logo->resize($width, $heigh);
            $logo->save($dir . $name, $quality);

        }else if(!is_file($dir . $name) && !is_file($dir . $image)){
            return false;
        }
        
        return $public . $name;
    }

    public static function render($image)
    {
        $di = Di::getDefault();

        $module = $di->get('router')->getModuleName();
        $module = 'frontend';
        $moduleAssetsDir = $module . 'AssetsDir';
        $moduleAssets = $module . 'Assets';

        $config = $di->get('config')->application;
        $dir = $config->$moduleAssetsDir;
        $public = $config->$moduleAssets;

        if(!file_exists($dir . $image) || !is_file($dir . $image)){
            return false;
        }
        return $public . $image;
    }

    public static function deleteImage($image)
    {
        $di = Di::getDefault();

        $module = $di->get('router')->getModuleName();
        $module = 'frontend';
        $moduleAssetsDir = $module . 'AssetsDir';
        $moduleAssets = $module . 'Assets';

        $config = $di->get('config')->application;
        $dir = $config->$moduleAssetsDir;
        $public = $config->$moduleAssets;

        if(file_exists($dir . $image)){
            $data = explode('/', $image);
            $img = explode('.', array_pop($data))[0];
            $filePath = implode('/', $data);
            $images = scandir($dir . $filePath);

            if(!empty($images)){
                foreach ($images as $key) {
                    if(strstr($key, $img) !== false){
                        @unlink($dir . $filePath . '/' . $key);
                    }
                }
            }
        }


        return true;
    }

}

