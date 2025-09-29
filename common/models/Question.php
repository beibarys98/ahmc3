<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%question}}".
 *
 * @property int $id
 * @property int $test_id
 * @property string $question
 * @property int|null $answer
 * @property string|null $img_path
 *
 * @property Test $test
 */
class Question extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%question}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['file', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],

            [['answer', 'img_path'], 'default', 'value' => null],
            [['test_id', 'question'], 'required'],
            [['test_id', 'answer'], 'integer'],
            [['question'], 'string'],
            [['img_path'], 'string', 'max' => 255],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['test_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'test_id' => 'Test ID',
            'question' => 'Question',
            'answer' => 'Answer',
            'img_path' => 'Img Path',
        ];
    }

    /**
     * Gets query for [[Test]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TestQuery
     */
    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\QuestionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\QuestionQuery(get_called_class());
    }

}
