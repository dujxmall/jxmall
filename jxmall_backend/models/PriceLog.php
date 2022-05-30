<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%price_log}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property float $price
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $is_delete
 * @property string|null $remarks
 * @property int $common_order_detail_id
 * @property int $buy_user_id
 * @property string $order_no
 */
class PriceLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%price_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id'], 'required'],
            [['mall_id', 'user_id', 'created_at', 'updated_at', 'deleted_at', 'is_delete', 'common_order_detail_id', 'buy_user_id'], 'integer'],
            [['price'], 'number'],
            [['order_no'], 'string', 'max' => 45],
            [['remarks'], 'string', 'max' => 128],
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
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'remarks' => 'Remarks',
            'buy_user_id' => 'buy_user_id',
            'order_no'=>'order_no'
        ];
    }
}
