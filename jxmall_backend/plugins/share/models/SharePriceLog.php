<?php

namespace app\plugins\share\models;

use app\models\BaseActiveRecord;
use Yii;


/**
 * This is the model class for table "{{%plugin_share_price_log}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $common_order_detail_id
 * @property float $price
 * @property int $status 0 未发放  1发放 2无效
 * @property int $share_order_id
 * @property int $updated_at
 * @property int $created_at
 * @property int $deleted_at
 * @property int $is_delete
 * @property int $common_order_id
 * @property int $goods_id
 * @property int $goods_type
 */
class SharePriceLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_share_price_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'common_order_detail_id', 'share_order_id', 'updated_at', 'created_at', 'deleted_at', 'goods_id'], 'required'],
            [['mall_id', 'user_id', 'common_order_detail_id', 'status', 'share_order_id', 'updated_at', 'created_at', 'deleted_at', 'is_delete', 'common_order_id', 'goods_id', 'goods_type'], 'integer'],
            [['price'], 'number'],
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
            'common_order_detail_id' => 'Common Order Detail ID',
            'price' => 'Price',
            'status' => '0 未发放  1发放 2无效',
            'share_order_id' => 'PointUser Order ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'common_order_id' => 'common_order_id',
            'goods_id' => 'goods_id',
            'goods_type' => 'goods_type'//0 商城商品
        ];
    }
}
