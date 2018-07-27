<?php

namespace App\Backend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Email as ElEmail;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;


class UsersForm extends Form
{
    /**
     * Инициализация формы
     */
    public $edit = false;

    public function initialize($entity = null, $options = [])
    {
        
        if(isset($options['edit'])){
            $this->edit = $options['edit'];
        }

        $name = new Text("name");
        $name->setLabel("Имя");
        $name->setFilters(["striptags","string", "trim"]);
        $name->addValidators(
            [
                new PresenceOf([
                        "message" => "Имя обязательно",
                    ])
            ]
        );
        $this->add($name);

        $surname = new Text("surname");
        $surname->setLabel("Фамилия");
        $surname->setFilters(["striptags","string", "trim"]);
        $this->add($surname);

        $middlename = new Text("middlename");
        $middlename->setLabel("Отчество");
        $middlename->setFilters(["striptags","string",]);
        $this->add($middlename);

        $email = new ElEmail("email");
        $email->setLabel("Email");
        $email->addValidators(
            [
                new PresenceOf(["message" => "Email обязательно"]),
                new Email(["message" => "Неверный Email"])
            ]
        );
        $this->add($email);

        $second_email = new ElEmail("second_email");
        $second_email->setLabel("Дополнительный Email");
        $email->addValidators(
            [
                new Email(["message" => "Неверный дополнительный Email"])
            ]
        );
        $this->add($second_email);

        if(!$this->edit){
            $password = new Password("password");
            $password->setLabel('Пароль');
            $password->addValidators(
                [
                    new PresenceOf(["message" => "Пароль обязательно",])
                ]
            );
            $this->add($password);
        }

        $phone = new Text("phone");
        $phone->setLabel('Телефон');
        $this->add($phone);

        $second_phone = new Text("second_phone");
        $second_phone->setLabel('Дополнительный телефон');
        $this->add($second_phone);

        $gender = new Select("gender",
            ['Мужской', 'Женский'],
            [
                "using"      => [
                    "id",
                    "name",
                ],
                "useEmpty"   => true,
                "emptyText"  => "Выберите пол",
                "emptyValue" => "",
            ]
        );
        $gender->setLabel('Пол');
        $this->add($gender);

        $active = new Select("active", ['Нет', 'Да']);
        $active->setDefault(1);
        $active->setLabel('Активный');
        $this->add($active);

        $banned = new Select("banned", ['Нет', 'Да']);
        $banned->setDefault(0);
        $banned->setLabel('Заблокированный');
        $this->add($banned);

        $email_confirmation = new Select("email_confirmation", ['Нет', 'Да']);
        $email_confirmation->setDefault(0);
        $email_confirmation->setLabel('Подтвержденный email');
        $this->add($email_confirmation);

        $i = 0;
        $roles = [];
        foreach ($this->acl->roles as $key) {
            if(++$i !== count($this->acl->roles)){
                $roles[] = $key;
            }
        }         
         
        $role_id = new Select("role_id", $roles);
        $role_id->setDefault(0);
        $role_id->setLabel('Тип пользователя');
        $this->add($role_id);
    }
}