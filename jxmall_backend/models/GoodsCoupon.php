<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods_coupon}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $goods_id
 * @property int $coupon_id
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 */
class GoodsCoupon extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_coupon}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'goods_id', 'coupon_id'], 'required'],
            [['mall_id', 'goods_id', 'coupon_id', 'is_delete', 'created_at', 'deleted_at', 'updated_at'], 'integer'],
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
            'goods_id' => 'Goods ID',
            'coupon_id' => 'Coupon ID',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
        ];
    }
}
