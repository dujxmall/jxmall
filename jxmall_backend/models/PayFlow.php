<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pay_flow}}".
 *
 * @property int $id
 * @property int|null $mall_id mall_id
 * @property int|null $user_id 用户id
 * @property string|null $order_no 订单号
 * @property string|null $class_name 订单类
 * @property string|null $wechat_order_no 微信交易号
 * @property float|null $pay_price 支付金额
 * @property int|null $type 0减少 1增加
 * @property string $pay_type wechat balance
 * @property int $is_delete 是否删除
 * @property int|null $created_at 创建时间戳
 * @property int|null $updated_at 更新时间
 * @property int|null $deleted_at 删除时间
 * @property string $created_time 创建时间
 * @property string|null $updated_time 更新时间
 * @property string|null $deleted_time 删除时间
 */
class PayFlow extends BaseActiveRecord
{
    const WECHAY_PAY = 'wechat_pay';
    const BALANCE_PAY = 'balance_pay';
    const TYPE_IN=1;
    const TYPE_OUT=0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pay_flow}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'type', 'is_delete', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['pay_price'], 'number'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['order_no', 'wechat_order_no','pay_type'], 'string', 'max' => 45],
            [['class_name'], 'string', 'max' => 255],
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
            'user_id' => '用户id',
            'order_no' => '订单号',
            'class_name' => '订单类',
            'wechat_order_no' => '微信交易号',
            'pay_price' => '支付金额',
            'type' => '0微信支付 1 余额支付',
            'is_delete' => '是否删除',
            'created_at' => '创建时间戳',
            'updated_at' => '更新时间',
            'deleted_at' => '删除时间',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
            'deleted_time' => '删除时间',
        ];
    }


    public static function saveFlow($pay_price, $order_no, $class_name, $wechat_order_no, $user_id = 0, $mall_id = 1, $type = 0, $pay_type = 'wechat')
    {
        $model = new self();
        $model->mall_id = $mall_id;
        $model->user_id = $user_id;
        $model->pay_price = $pay_price;
        $model->order_no = $order_no;
        $model->wechat_order_no = $wechat_order_no;
        $model->class_name = $class_name;
        $model->type = $type;
        $model->pay_type = $pay_type;
        $model->save();
    }
}
