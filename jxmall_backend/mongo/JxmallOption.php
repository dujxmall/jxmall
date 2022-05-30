<?php

namespace app\mongo;

use Yii;

/**
 * This is the model class for collection "jxmall_option".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $mall_id
 * @property mixed $name
 * @property mixed $value
 * @property mixed $created_time
 * @property mixed $updated_at
 * @property mixed $deleted_at
 * * @property mixed $created_at
 * @property mixed $is_delete
 *
 *
 *
 */
class JxmallOption extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return 'jxmall_option';
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'mall_id',
            'name',
            'value',
            'created_time',
            'updated_at',
            'deleted_at',
            'created_at',
            'is_delete'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'name', 'value', 'created_at', 'deleted_at', 'updated_at', 'created_time'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'mall_id' => 'mall_id',
            'name' => 'Field Key',
            'value' => 'value',
            'created_at' => 'created_at',
            'deleted_at' => 'deleted_at',
            'updated_at' => 'updated_at',
            'created_time' => 'created_time'
        ];
    }
}
