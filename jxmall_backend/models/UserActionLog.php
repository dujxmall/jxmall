<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_action_log}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int|null $user_id
 * @property string|null $before_update
 * @property string|null $after_update
 * @property string|null $remarks
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int $is_delete
 * @property string|null $log_at 更新时间
 */
class UserActionLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_action_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'updated_at', 'created_at', 'deleted_at', 'is_delete'], 'integer'],
            [['before_update', 'after_update'], 'string'],
            [['log_at'], 'safe'],
            [['remarks'], 'string', 'max' => 255],
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
            'before_update' => 'Before Update',
            'after_update' => 'After Update',
            'remarks' => 'Remarks',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'log_at' => 'Log At',
        ];
    }
}
