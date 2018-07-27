<?php
namespace App\Models;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Db\RawValue;

use App\Models\Model;
use App\Models\Posts;
use App\Models\Images;
use App\Models\Pages;
use App\Models\Categories;
/**
 * Vokuro\Models\Users
 * All the users registered in the application
 */
class MenuItems extends Model
{
    public $id;
    public $alias;
    public $name;
    public $parent_id;
    public $position;

    public static $TYPE_ARTICLE = 0;
    public static $TYPE_PAGE = 1;
    public static $TYPE_CATEGORY = 2;

    
    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // if field "is required"
        if (!$this->alias) {
            $this->alias = new RawValue('default');
        }

        if (!$this->parent_id) {
            $this->parent_id = new RawValue('default');
        }

        if (!$this->position) {
            $this->position = new RawValue('default');
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
		if (!$this->parent_id) {
            $this->parent_id = new RawValue('default');
        }
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
        $this->setup(['notNullValidations' => false]);
        $this->hasOne('parent_id', '\\'. __NAMESPACE__ . '\MenuItems', 'id', ['alias' => 'parent']);
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
            'parent_id' => 'Родительский пункт меню',
            'type_id' => 'Тип элемента меню',
            'item_id' => 'Элемент',
            'menu_id' => 'Меню',
            'alias' => 'Адрес',
            'name' => 'Название',
            'text' => 'Описание',
            'active' => 'Активный',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'position' => 'Порядковый номер',
            'tags' => 'Теги',
        ];
        return $id ? $array[$id] : $array;
    }

    public function getType($id = false)
    {
        $array = [
            self::$TYPE_PAGE => 'Страница',
            self::$TYPE_ARTICLE => 'Запись',
            self::$TYPE_CATEGORY => 'Категория'
        ];

        return $id ? $array[$id] : $array;
    }

    public static function getTypeModel($type)
    {
    	switch ($type) {
            case self::$TYPE_ARTICLE: 	return Posts::class;
            case self::$TYPE_PAGE: 		return Pages::class;
            case self::$TYPE_CATEGORY: 	return Categories::class;
        }

        return false;
    }

    public static function getTypeData($type, $typeId = false)
    {
    	$model = self::getTypeModel($type);

    	if($model){
	    	if($typeId){
	    		return $model::findFirst(["active = 1 AND id = :id:", "bind" => ["id" => $typeId]]);
	    	}
	    	return $model::find(['active = 1']);
    	}

        return false;
    }

}