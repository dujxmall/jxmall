<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%charge_log}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $admin_id
 * @property int $user_id
 * @property int $type 0余额 1积分
 * @property float $num
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property float $before_num 充值之前
 * @property float $after_num 充值之后
 * @property string|null $remarks
 */
class ChargeLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%charge_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'admin_id', 'user_id'], 'required'],
            [['remarks'],'string'],
            [['mall_id', 'admin_id', 'user_id', 'type', 'is_delete', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['num', 'before_num', 'after_num'], 'number'],
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
            'admin_id' => 'Admin ID',
            'user_id' => 'User ID',
            'type' => '0余额 1积分',
            'num' => 'Num',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'before_num' => '充值之前',
            'after_num' => '充值之后',
            'remarks'=>'备注'
        ];
    }
}
