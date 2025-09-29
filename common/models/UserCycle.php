<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_cycle}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $course_id
 * @property int|null $cycle_id
 * @property string|null $type
 * @property string|null $status
 *
 * @property Course $course
 * @property Cycle $cycle
 * @property User $user
 */
class UserCycle extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_cycle}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'cycle_id', 'type', 'status'], 'default', 'value' => null],
            [['user_id'], 'required'],
            [['user_id', 'course_id', 'cycle_id'], 'integer'],
            [['type', 'status'], 'string', 'max' => 100],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['cycle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cycle::class, 'targetAttribute' => ['cycle_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Аккаунт ID',
            'course_id' => 'Курс',
            'cycle_id' => 'Цикл',
            'type' => 'Түрі',
            'status' => 'Статус',
        ];
    }

    /**
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CourseQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    /**
     * Gets query for [[Cycle]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CycleQuery
     */
    public function getCycle()
    {
        return $this->hasOne(Cycle::class, ['id' => 'cycle_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\UserCycleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserCycleQuery(get_called_class());
    }

}
