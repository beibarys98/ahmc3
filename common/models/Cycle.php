<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%cycle}}".
 *
 * @property int $id
 * @property int $course_id
 * @property string $title_kz
 * @property string $title_ru
 * @property string $month
 * @property string $duration
 *
 * @property Course $course
 */
class Cycle extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cycle}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'title_kz', 'title_ru'], 'required'],
            [['course_id'], 'integer'],
            [['title_kz', 'title_ru'], 'string', 'max' => 255],
            [['month', 'duration'], 'string', 'max' => 50],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'title_kz' => 'Title Kz',
            'title_ru' => 'Title Ru',
            'month' => 'Month',
            'duration' => 'Duration',
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
     * {@inheritdoc}
     * @return \common\models\query\CycleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CycleQuery(get_called_class());
    }

}
