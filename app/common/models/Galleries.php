<?php
namespace App\Models;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Db\RawValue;

use App\Models\Model;
use App\Models\Posts;
use App\Models\Images;
use App\Models\GalleryItem;
/**
 * Vokuro\Models\Users
 * All the users registered in the application
 */
class Galleries extends Model
{
    public $id;
    public $name;

    public $imgUploadDir = 'img/galleries/';

    
    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // if field "is required"
        // if (!$this->alias) {
        //     $this->alias = new RawValue('default');
        // }
    }

    public function beforeCreate()
    {
        $this->created_at = $this->updated_at = date('Y-m-d H:i');   
    }

    public function beforeSave()
    {
        // if(!empty($this->alias)){
        //     $this->alias = $this->str2url($this->name);
        // }
    }

    public function beforeUpdate()
    {
        $this->updated_at = date('Y-m-d H:i');
    }
    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {
    }

    public function beforeDelete()
    {
        if($this->gallery){
            foreach ($this->gallery as $key) {
                if($key->path){
                    Images::deleteImage($this->imgUploadDir . $key->path);
                }
            }
        }

        GalleryItem::query()->where('gallery_id = ' . $this->id)->execute()->delete();
    }
    /**
     * Validate that emails are unique across users
     */
    public function validation()
    {
        // $validator = new Validation();
        // $validator->add('country', new PresenceOf([
        //     "message" => "Поле \"" . $this->getLabels('country') . "\" обязательно"
        // ]));
        // return $this->validate($validator);
    }



    public function initialize()
    {
        // $this->belongsTo('profilesId', __NAMESPACE__ . '\Profiles', 'id', [
        //     'alias' => 'profile',
        //     'reusable' => true
        // ]);
        // switch off
        // $this->setup(['notNullValidations' => true]);
        $this->hasOne('category_id', '\\'. __NAMESPACE__ . '\Categories', 'id', ['alias' => 'category']);
        // $this->hasOne('notification_id', '\\'. __NAMESPACE__ . '\Users', 'id', ['alias' => 'notification_user']);
        $this->hasMany(
            'id', 
            '\\' . __NAMESPACE__ . "\GalleryItem",
            "gallery_id", 
            [
                'alias' => 'gallery'
            ]
        );
        // $this->hasManyToMany(
        //     'id', 
        //     '\\'. __NAMESPACE__ . "\CompanyUsers", 
        //     "company_id", "user_id", 
        //     '\\'. __NAMESPACE__ . "\Users", 
        //     "id", 
        //     [
        //         'alias' => 'companyUsers'
        //     ]
        // );
        // $this->hasMany(
        //     'id', 
        //     '\\'. __NAMESPACE__ . "\CompanyDocuments",
        //     "company_id", 
        //     [
        //         'alias' => 'companyDocuments'
        //     ]
        // );
    }

    public function getLabels($id = false)
    {
        $array = [
            'id' => 'ID',
            'name' => 'Название',
            'active' => 'Активный',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
        return $id ? $array[$id] : $array;
    }

    public function deleteItem($id)
    {
        $model = GalleryItem::findFirstById($id);

        if ($model) {
            Images::deleteImage($this->imgUploadDir . $model->path);
            $model->delete();
        }
    }


    public function upload($request)
    {
        if ($request->hasFiles() == true) {

            foreach ($request->getUploadedFiles() as $file) {
            
                if($file->getSize()){
                    $file->moveTo($this->getDI()->get('config')->application->frontendAssetsDir . $this->imgUploadDir . $file->getName());

                    $galleryItem = new GalleryItem();
                    $galleryItem->gallery_id = $this->id;
                    $galleryItem->path = $file->getName();
                    $galleryItem->position = 0;
                    $galleryItem->save();
                }
            }
            return $this->save();
       }
       return true;
    }

    public function render($template = false)
    {
        
        echo '';
    }

}