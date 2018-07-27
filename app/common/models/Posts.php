<?php
namespace App\Models;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Db\RawValue;
use App\Models\Model;
use App\Models\Posts;
use App\Models\Images;
/**
 * Vokuro\Models\Users
 * All the users registered in the application
 */
class Posts extends Model
{
    public $id;
    public $logo;
    public $imgUploadDir = 'img/posts/';
    public $alias;
    public $name;

    
    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // if field "is required"
        if (!$this->alias) {
            $this->alias = new RawValue('default');
        }
    }

    public function beforeCreate()
    {
        $this->created_at = $this->updated_at = date('Y-m-d H:i');   
    }

    public function beforeSave()
    {
        if( 
            isset($_FILES['logo']['name']) && 
            !empty($_FILES['logo']['name']) && 
            !empty( $this->logo ) && 
            ($_FILES['logo']['name'] !== $this->logo) 
        ){
            $this->beforeDelete();
        }

        if(!empty($this->alias)){
            $this->alias = $this->str2url($this->name);
        }
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
        if($this->logo){
            Images::deleteImage($this->imgUploadDir . $this->logo);
        }
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

    public function upload($request)
    {
        // Check if the user has uploaded files
        if ($request->hasFiles() == true) {
            // Print the real file names and their sizes
            foreach ($request->getUploadedFiles() as $file) {
                
                if($file->getSize()){
                    $key = $file->getKey();
                    if($key === 'logo'){
                        $file->moveTo($this->getDI()->get('config')->application->frontendAssetsDir . $this->imgUploadDir . $file->getName());
                        $key = $file->getKey();
                        $this->$key = $file->getName();
                    }
                }
            }
            return $this->save();
       }
       return true;
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
            '\\' . __NAMESPACE__ . "\Tags",
            "post_id", 
            [
                'alias' => 'tags'
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
            'logo' => 'Логотип',
            'user_id' => 'Автор',
            'category_id' => 'Категория',
            'alias' => 'Адрес',
            'name' => 'Название',
            'description' => 'Описание',
            'short_description' => 'Короткое описание',
            'active' => 'Активный',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',

            'tags' => 'Теги',
        ];
        return $id ? $array[$id] : $array;
    }

}