<?php
namespace App\Models;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Db\RawValue;

use App\Models\Model;
use App\Models\Posts;
use App\Models\Images;
use App\Models\Galleries;
/**
 * Vokuro\Models\Users
 * All the users registered in the application
 */
class Pages extends Model
{
    public $id;
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

    public function getText()
    {
        $posStart = strpos ($this->text, '{{');
        $posEnd = strpos ($this->text, '}}');

        if($posStart && $posEnd && ($posStart < $posEnd) ){
            $galleryJson = mb_substr($this->text, $posStart - 7, $posEnd - $posStart);

            $galleryArray = json_decode(htmlspecialchars_decode($galleryJson), true);

            $text = mb_substr($this->text, 0, $posStart - 8);
            if($galleryArray['id']){
                $gallery = Galleries::findFirstById($galleryArray['id']);
                $text .= $gallery->render(isset($galleryArray['template']) ? $galleryArray['template'] : false);
            }
            $text .= mb_substr($this->text, $posEnd);

            $this->text = $text;
        }


        return $this->text;
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
            'text' => 'Описание',
            'active' => 'Активный',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',

            'tags' => 'Теги',
        ];
        return $id ? $array[$id] : $array;
    }

}