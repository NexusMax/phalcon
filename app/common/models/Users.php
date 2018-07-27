<?php
namespace App\Models;

use Phalcon\Validation;
use Phalcon\Security;
use Phalcon\Validation\Validator\Uniqueness;
use App\Models\Model;

/**
 * Vokuro\Models\Users
 * All the users registered in the application
 */
class Users extends Model
{

    public $id;
    public $name;
    public $surname;
    public $middlename;
    public $email;
    public $second_email;
    public $password;
    public $company_id;
    public $company_position;
    public $phone;
    public $second_phone;
    public $gender;
    public $active;
    public $banned;
    public $email_confirmation;
    public $role_id;

    public $created_at;
    public $updated_at;

    public $companies = [];
    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {

    }

    public function beforeCreate()
    {   
        $security = new Security;
        $this->created_at = $this->updated_at = date('Y-m-d H:i');
        $this->password = $security->hash($this->password);
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

    /**
     * Validate that emails are unique across users
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add('email', new Uniqueness([
            "message" => "Такой Email уже зарегистрирован"
        ]));

        return $this->validate($validator);
    }
    public function initialize()
    {

        // $this->hasOne('company', '\\'. __NAMESPACE__ . '\Companies', 'id', ['alias' => 'company']);

        // $this->hasManyToMany(
        //     'id', 
        //     '\\'. __NAMESPACE__ . "\CompanyUsers", 
        //     "user_id", "company_id", 
        //     '\\'. __NAMESPACE__ . "\Companies", 
        //     "id", 
        //     [
        //         'alias' => 'company'
        //     ]
        // );

        // $this->belongsTo('profilesId', __NAMESPACE__ . '\Profiles', 'id', [
        //     'alias' => 'profile',
        //     'reusable' => true
        // ]);

        // $this->hasMany('id', __NAMESPACE__ . '\SuccessLogins', 'usersId', [
        //     'alias' => 'successLogins',
        //     'foreignKey' => [
        //         'message' => 'User cannot be deleted because he/she has activity in the system'
        //     ]
        // ]);
    }


    public function getLabels($id = false)
    {
        $array = [
            'id' => 'ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'middlename' => 'Отчество',
            'fio' => 'ФИО',
            'email' => 'Email',
            'second_email' => 'Дополнительный email',
            'password' => 'Номер / серия документа',
            'company_id' => 'Компания',
            'company_position' => 'Должность',
            'phone' => 'Телефон',
            'second_phone' => 'Дополнительный телефон',
            'gender' => 'Пол',
            'active' => 'Активный',
            'status' => 'Статус',
            'banned' => 'Заблокирован',
            'email_confirmation' => 'Подтверждение почты',
            'role_id' => 'Група пользователя',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
        return $id ? $array[$id] : $array;
    }
}
