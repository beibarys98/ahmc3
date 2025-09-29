<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property int $user_id
 * @property int $cycle_id
 * @property int $file_type_id
 * @property string|null $path
 *
 * @property UserCycle $cycle
 * @property User $user
 */
class File extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['path'], 'default', 'value' => null],
            [['user_id', 'cycle_id', 'file_type_id'], 'required'],
            [['user_id', 'cycle_id', 'file_type_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['cycle_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserCycle::class, 'targetAttribute' => ['cycle_id' => 'id']],
            [['file_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FileType::class, 'targetAttribute' => ['file_type_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'cycle_id' => 'Cycle ID',
            'type' => 'Type',
            'path' => 'Path',
        ];
    }

    /**
     * Gets query for [[Cycle]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserCycleQuery
     */
    public function getCycle()
    {
        return $this->hasOne(UserCycle::class, ['id' => 'cycle_id']);
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
     * @return \common\models\query\FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\FileQuery(get_called_class());
    }

}
