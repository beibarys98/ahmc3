<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%test}}".
 *
 * @property int $id
 * @property int $cycle_id
 * @property string $title_kz
 * @property string $title_ru
 * @property string $status
 * @property string $duration
 * @property string $type
 *
 * @property Cycle $cycle
 */
class Test extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['file', 'file', 'skipOnEmpty' => false, 'extensions' => 'doc, docx'],

            [['cycle_id', 'title_kz', 'title_ru', 'status', 'duration', 'type'], 'required'],
            [['cycle_id'], 'integer'],
            [['duration'], 'safe'],
            [['title_kz', 'title_ru'], 'string', 'max' => 255],
            [['status', 'type'], 'string', 'max' => 50],
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
            'cycle_id' => 'Цикл',
            'title_kz' => 'Атауы',
            'title_ru' => 'Название',
            'status' => 'Статус',
            'duration' => 'Ұзақтығы',
            'file' => 'Файл'
        ];
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
     * {@inheritdoc}
     * @return \common\models\query\TestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TestQuery(get_called_class());
    }

}
