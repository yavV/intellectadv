<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "profile_fields".
 *
 * @property integer $id
 * @property integer $profile_id
 * @property string $field_name
 * @property string $field_value
 *
 * @property Profile $profile
 */
class ProfileFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['profile_id', 'field_name', 'field_value'], 'required'],
            [['profile_id'], 'integer'],
            [['field_name', 'field_value'], 'string', 'max' => 32],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['profile_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'profile_id' => 'Profile ID',
            'field_name' => 'Наименование',
            'field_value' => 'Значение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'profile_id']);
    }
}
