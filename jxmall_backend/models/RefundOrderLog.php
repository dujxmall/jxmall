<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%refund_order_log}}".
 *
 * @property int $id
 * @property int|null $mall_id mall_id
 * @property string|null $order_no 订单号
 * @property string|null $refund_order_no 退款单号
 * @property float|null $refund_price 退款金额
 * @property string|null $remark 备注
 * @property string|null $from_pay_type 退款来源 wehcat join
 * @property int $is_delete 是否删除
 * @property int|null $created_at 创建时间戳
 * @property int|null $updated_at 更新时间
 * @property int|null $deleted_at 删除时间
 * @property string $created_time 创建时间
 * @property string|null $updated_time 更新时间
 * @property string|null $deleted_time 删除时间
 * @property integer $status
 * @property string $message
 */
class RefundOrderLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%refund_order_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'is_delete', 'created_at', 'updated_at', 'deleted_at','status'], 'integer'],
            [['refund_price'], 'number'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['order_no', 'refund_order_no', 'from_pay_type'], 'string', 'max' => 45],
            [['remark'], 'string', 'max' => 255],
            [['message'],'string','max'=>5120]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'mall_id',
            'order_no' => '订单号',
            'refund_order_no' => '退款单号',
            'refund_price' => '退款金额',
            'remark' => '备注',
            'from_pay_type' => '退款来源 wehcat join',
            'is_delete' => '是否删除',
            'created_at' => '创建时间戳',
            'updated_at' => '更新时间',
            'deleted_at' => '删除时间',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
            'deleted_time' => '删除时间',
        ];
    }
}
