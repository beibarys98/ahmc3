<?php

namespace frontend\models;

use common\models\UserCycle;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $name;
    public $organization;
    public $course_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => 'Толтырыңыз!'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Бос емес!'],
            ['username', 'match', 'pattern' => '/^8\d{10}$/', 'message' => 'Мысалы: 87478725287'],

            ['name', 'trim'],
            ['name', 'required', 'message' => 'Толтырыңыз!'],
            ['name', 'match', 'pattern' => '/^(?=.{3,255}$)[А-Яа-яЁёІіҢңҒғҮүҰұҚқӨөҺһӘә\-]+(?:\s+[А-Яа-яЁёІіҢңҒғҮүҰұҚқӨөҺһӘә\-]+)+$/u',
                'message' => 'Мысалы: Мухаммедьяров Бейбарыс'],

            ['organization', 'trim'],
            ['organization', 'required', 'message' => 'Толтырыңыз!'],
            ['organization', 'string', 'max' => 255, 'tooLong' => 'Тым ұзын!'],

            ['course_id', 'required', 'message' => 'Толтырыңыз!'],
            ['course_id', 'integer'],
            ['course_id', 'exist', 'targetClass' => '\common\models\Course', 'targetAttribute' => 'id']
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->name = $this->name;
        $user->organization = $this->organization;
        $user->save(false);

        $user_cycle = new UserCycle();
        $user_cycle->user_id = $user->id;
        $user_cycle->course_id = $this->course_id;
        $user_cycle->status = 'registered';
        $user_cycle->save(false);

        return true;
    }
}
