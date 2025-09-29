<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%file_type}}".
 *
 * @property int $id
 * @property string $file
 * @property string $type
 */
class FileType extends \yii\db\ActiveRecord
{
    public $singleFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['singleFile', 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],

            [['file', 'type'], 'required'],
            [['file'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'File',
            'type' => 'Type',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\FileTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\FileTypeQuery(get_called_class());
    }

}
