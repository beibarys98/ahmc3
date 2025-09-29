<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%answer}}".
 *
 * @property int $id
 * @property int $question_id
 * @property string $answer
 * @property string|null $img_path
 *
 * @property Question $question
 */
class Answer extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%answer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['file', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],

            [['img_path'], 'default', 'value' => null],
            [['question_id', 'answer'], 'required'],
            [['question_id'], 'integer'],
            [['answer'], 'string', 'max' => 1000],
            [['img_path'], 'string', 'max' => 255],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'Question ID',
            'answer' => 'Answer',
            'img_path' => 'Img Path',
        ];
    }

    /**
     * Gets query for [[Question]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\QuestionQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::class, ['id' => 'question_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\AnswerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AnswerQuery(get_called_class());
    }

}
