<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_refund}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $order_id
 * @property int $order_detail_id
 * @property int $goods_id
 * @property string|null $pic_list
 * @property string|null $content
 * @property int $type 退款退货 1 换货
 * @property int $status 0 未处理
 * @property int $is_delete
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property float $refund_price
 * @property string|null $order_no
 * @property int $is_refund
 */
class OrderRefund extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_refund}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'order_id', 'order_detail_id', 'goods_id'], 'required'],
            [['mall_id', 'user_id', 'order_id', 'order_detail_id', 'goods_id', 'type', 'status', 'is_delete', 'updated_at', 'created_at', 'deleted_at', 'is_refund'], 'integer'],
            [['refund_price'], 'number'],
            [['order_no'], 'string', 'max' => 45],
            [['pic_list'], 'string', 'max' => 2048],
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
            'order_id' => 'Order ID',
            'order_detail_id' => 'Order Detail ID',
            'goods_id' => 'Goods ID',
            'pic_list' => 'Pic List',
            'content' => 'Content',
            'type' => '退款退货 1 换货',
            'status' => '0 未处理',
            'is_delete' => 'Is Delete',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'refund_price' => 'Refund Price',
            'order_no' => 'order_no',
            'is_refund' => '是否退款'
        ];
    }

    public static function generateOrderNo($user_id)
    {
        while (true) {
            $no = 'R' . date('YmdHis', time()) . time() . $user_id;
            $is_exist = self::find()->where(['order_no' => $no])->exists();
            if (!$is_exist) {
                return $no;
            }
        }
    }

}
