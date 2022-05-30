<?php

namespace app\mongo;

use Yii;
use yii\mongodb\Exception;

/**
 * This is the model class for collection "user_action_log".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $mall_id
 * @property mixed $user_id
 * @property mixed $before_update
 * @property mixed $after_update
 * @property mixed $remarks
 * @property mixed $updated_at
 * @property mixed $created_at
 * @property mixed $deleted_at
 * @property mixed $is_delete
 * @property mixed $created_time
 */
class JxmallUserActionLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return 'jxmall_user_action_log';
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'mall_id',
            'user_id',
            'before_update',
            'after_update',
            'remarks',
            'created_time',
            'updated_at',
            'created_at',
            'deleted_at',
            'is_delete',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mall_id', 'user_id', 'before_update', 'after_update', 'remarks', 'updated_at', 'created_at', 'deleted_at', 'is_delete','created_time'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',

            'mall_id' => 'Mall ID',
            'user_id' => 'User ID',
            'before_update' => 'Before Update',
            'after_update' => 'After Update',
            'remarks' => 'Remarks',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'created_time'=>'created_time'
        ];
    }


}
