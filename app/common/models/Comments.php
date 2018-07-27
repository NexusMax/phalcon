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
class Comments extends Model
{
    public $id;
    public $logo;
    public $parent_id;
    /**
    notNullable variables
    */
    
    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // if field "is required"
        if (!$this->parent_id) {
            $this->parent_id = new RawValue('default');
        }
    }

    public function beforeCreate()
    {
        $this->created_at = $this->updated_at = date('Y-m-d H:i');   
    }

    public function beforeSave()
    {
        
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
        // $this->hasOne('category_id', '\\'. __NAMESPACE__ . '\Categories', 'id', ['alias' => 'category']);
        $this->hasOne('user_id', '\\'. __NAMESPACE__ . '\Users', 'id', ['alias' => 'user']);
        $this->hasOne('post_id', '\\'. __NAMESPACE__ . '\Posts', 'id', ['alias' => 'post']);
        $this->hasOne('parent_id', '\\'. __NAMESPACE__ . '\Comments', 'id', ['alias' => 'parent']);
        // $this->hasMany(
        //     'id', 
        //     '\\'. __NAMESPACE__ . "\CompanySites",
        //     "company_id", 
        //     [
        //         'alias' => 'companySites'
        //     ]
        // );
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
            'parent_id' => 'Родительский комментарий',
            'user_id' => 'Пользователь',
            'post_id' => 'Запись',
            'text' => 'Комментарий',
            'active' => 'Активный',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
        return $id ? $array[$id] : $array;
    }
}