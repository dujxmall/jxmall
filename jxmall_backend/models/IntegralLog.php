<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%integral_log}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $integral
 * @property string|null $content
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $is_delete
 * @property int $type 0 支出 1 收益
 * @property int current
 */
class IntegralLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%integral_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id'], 'required'],
            [['mall_id', 'user_id', 'integral', 'created_at', 'deleted_at', 'updated_at', 'is_delete', 'type', 'current'], 'integer'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'Mall ID',
            'user_id' => 'User ID',
            'integral' => 'Integral',
            'content' => 'Content',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
            'type' => '0 支出 1 收益',
            'current' => '当前'
        ];
    }
}
