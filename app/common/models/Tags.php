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
class Tags extends Model
{
   

	public function beforeCreate()
    {
        $this->created_at = $this->updated_at = date('Y-m-d H:i');   
    }

    public function beforeUpdate()
    {
        $this->updated_at = date('Y-m-d H:i');
    }

    public function initialize()
    {
        // $this->belongsTo('profilesId', __NAMESPACE__ . '\Profiles', 'id', [
        //     'alias' => 'profile',
        //     'reusable' => true
        // ]);
        // switch off
        // $this->setup(['notNullValidations' => true]);
    
        // $this->hasOne('notification_id', '\\'. __NAMESPACE__ . '\Users', 'id', ['alias' => 'notification_user']);
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
            'post_id' => 'Запись',
            'alias' => 'Адрес',
            'name' => 'Название',
            'active' => 'Активный',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
        return $id ? $array[$id] : $array;
    }

}