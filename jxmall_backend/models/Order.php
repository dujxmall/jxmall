<?php

namespace app\models;

use app\events\OrderEvent;
use app\helpers\SerializeHelper;
use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property float $total_price
 * @property float $express_price
 * @property float $pay_price
 * @property int $status
 * @property int $is_finish
 * @property int|null $finish_at
 * @property int $is_send
 * @property int|null $send_at
 * @property int $is_confirm
 * @property int|null $confirm_at
 * @property int $apply_cancel
 * @property int|null $apply_cancel_at
 * @property int|null $cancel_at
 * @property int $is_cancel
 * @property int $apply_refund
 * @property int|null $apply_refund_at
 * @property int $is_refund 是否已经退款
 * @property int $refund_type 0退款退货，1换货
 * @property float $refund_price 退款金额
 * @property int $agree_refund 统一售后申请
 * @property int|null $refund_operation_at 操作时间
 * @property int|null $refund_at 退款时间
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int $mch_id 商户ID
 * @property string|null $address
 * @property string|null $province_code 省ID
 * @property string|null $city_code
 * @property string|null $county_code
 * @property string|null $town_code
 * @property int $is_pay
 * @property int $pay_time
 * @property string $order_no
 * @property int $pay_type
 * @property string $name
 * @property string $mobile
 * @property string $remarks
 * @property User $user
 * @property int $send_type
 * @property int $express_log_id
 * @property string $coupon_cut_price
 * @property int $use_coupon_goods_id
 * @property int $user_coupon_id
 * @property int $is_use_integral
 * @property float $integral_cut_price
 * @property int $use_integral
 * @property integer $is_apply_send 是否申请发货
 * @property string $apply_send_time
 */
class Order extends BaseActiveRecord
{
    //0 待付款
    //1 代发货
    //2 待收货
    //3 确认收货
    //4 已完成
    //5 取消
    //6 申请售后

    const STATUS_NOT_PAY = 0;
    const STATUS_NOT_SEND = 1;
    const STATUS_NOT_CONFIRM = 2;
    const STATUS_IS_CONFIRM = 3;
    const STATUS_IS_COMPLETE = 4;  //已完成的订单不可再进行其他操作
    const STATUS_CANCEL = 5;
    const STATUS_APPLY_REFUND = 6;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id'], 'required'],
            [['mall_id','is_apply_send', 'use_integral', 'is_use_integral', 'user_coupon_id', 'use_coupon_goods_id', 'express_log_id', 'send_type', 'user_id', 'status', 'is_finish', 'finish_at', 'is_send', 'send_at', 'is_confirm', 'confirm_at', 'apply_cancel', 'apply_cancel_at', 'cancel_at', 'is_cancel', 'apply_refund', 'apply_refund_at', 'is_refund', 'refund_type', 'agree_refund', 'refund_operation_at', 'refund_at', 'is_delete', 'created_at', 'updated_at', 'deleted_at', 'mch_id', 'is_pay', 'pay_time', 'pay_type'], 'integer'],
            [['total_price', 'express_price', 'pay_price', 'refund_price', 'integral_cut_price', 'coupon_cut_price'], 'number'],
            [['address', 'remarks'], 'string', 'max' => 255],
            [['order_no', 'name','apply_send_time'], 'string', 'max' => 45],
            [['mobile'], 'string', 'max' => 11],
            [['province_code', 'city_code', 'county_code', 'town_code'], 'string', 'max' => 20],
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
            'total_price' => 'Total Price',
            'express_price' => 'Express Price',
            'pay_price' => 'Pay Price',
            'status' => 'Status',
            'is_finish' => 'Is Finish',
            'finish_at' => 'Finish At',
            'is_send' => 'Is Send',
            'send_at' => 'Send At',
            'is_confirm' => 'Is Confirm',
            'confirm_at' => 'Confirm At',
            'apply_cancel' => 'Apply Cancel',
            'apply_cancel_at' => 'Apply Cancel At',
            'cancel_at' => 'Cancel At',
            'is_cancel' => 'Is Cancel',
            'apply_refund' => 'Apply Refund',
            'apply_refund_at' => 'Apply Refund At',
            'is_refund' => '是否已经退款',
            'refund_type' => '0退款退货，1换货',
            'refund_price' => '退款金额',
            'agree_refund' => '统一售后申请',
            'refund_operation_at' => '操作时间',
            'refund_at' => '退款时间',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'mch_id' => '商户ID',
            'address' => 'Address',
            'province_code' => '省ID',
            'city_code' => 'City Code',
            'county_code' => 'County Code',
            'town_code' => 'Town Code',
            'is_pay' => 'is_pay',
            'pay_time' => 'pay_time',
            'order_no' => 'order_no',
            'pay_type' => 'pay_type',
            'name' => 'name',
            'mobile' => 'mobile',
            'remarks' => 'remarks',
            'send_type' => 'send_type',
            'express_log_id' => 'express_log_id',
            'coupon_cut_price' => '优惠券减少的金额',
            'use_coupon_goods_id' => 'use_coupon_goods_id',
            'user_coupon_id' => 'user_coupon_id',
            'is_use_integral' => 'is_use_integral',
            'use_integral' => 'use_integral',
            'integral_cut_price' => '积分抵扣金额'
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        //0 待付款
        //1 代发货
        //2 待收货
        //3 确认收货
        //4 已完成
        //5 取消
        //6 申请售后
        if (!$insert) {
            if (isset($changedAttributes['status'])) {
                $event = new OrderEvent();
                $event->order_id = $this->id;
                Yii::$app->trigger(OrderEvent::STATUS_CHANGE, $event);
            }
        } else {
            $event = new OrderEvent();
            $event->order_id = $this->id;
            Yii::$app->trigger(OrderEvent::ORDER_CREATED, $event);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
