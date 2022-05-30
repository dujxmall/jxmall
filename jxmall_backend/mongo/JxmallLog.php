<?php

namespace app\mongo;

use Yii;

/**
 * This is the model class for collection "jxmall_log".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $type
 * @property mixed $message
 * @property mixed $trace
 * @property mixed $is_delete
 * @property mixed $created_at
 * @property mixed $created_time
 * @property mixed $updated_at
 * @property mixed $deleted_at
 * @property mixed $file
 * @property mixed $line
 */
class JxmallLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return 'jxmall_log';
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'type',
            'message',
            'trace',
            'is_delete',
            'created_at',
            'created_time',
            'updated_at',
            'deleted_at',
            'file',
            'line'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'message', 'trace', 'is_delete', 'created_at', 'created_time', 'updated_at', 'deleted_at','file','line'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'type' => 'Type',
            'message' => 'Message',
            'trace' => 'Trace',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'created_time' => 'Created Time',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'file'=>'file',
            'line'=>'line'
        ];
    }
}
