<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%balance_log}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $type 0 支出 1 收入
 * @property string|null $content
 * @property float $money 使用金额
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int $is_delete
 * @property float $current 当前金额
 * @property integer $from_user_id
 * @property integer $to_user_id
 */
class BalanceLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%balance_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id'], 'required'],
            [['mall_id', 'from_user_id', 'to_user_id', 'user_id', 'type', 'deleted_at', 'updated_at', 'created_at', 'is_delete'], 'integer'],
            [['money', 'current'], 'number'],
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
            'type' => '0 支出 1 收入',
            'content' => 'Content',
            'money' => '使用金额',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'is_delete' => 'Is Delete',
            'current' => '当前金额',
            'from_user_id'=>'from_user_id',
            'to_user_id'=>'to_user_id'
        ];
    }
}
