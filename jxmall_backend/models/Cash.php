<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cash}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property float $cash_price
 * @property float $service_price
 * @property float $price
 * @property int $status 0 带处理 1通过  2 拒绝
 * @property int $is_price 是否打款
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int $is_delete
 * @property int $type
 * @property int|null $updated_at
 * @property string|null $cash_type
 * @property string|null $bank_name
 * @property string|null $account
 * @property string|null $receipt
 * @property string|null $name
 * @property int $is_manual
 * @property string $bank_code
 * @property string $order_no
 * @property string $reason
 * @property integer $is_joinpay
 * @property string $joinpay_desc
 * @property integer $joinpay_status
 */
class Cash extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cash}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'cash_type'], 'required'],
            [['reason'], 'string'],
            [['joinpay_desc'], 'string', 'max' => 5120],
            [['cash_type', 'bank_name', 'account', 'receipt', 'name', 'bank_code', 'order_no'], 'string'],
            [['mall_id', 'joinpay_status', 'is_joinpay', 'user_id', 'status', 'is_price', 'created_at', 'deleted_at', 'is_delete', 'updated_at', 'type', 'is_manual'], 'integer'],
            [['cash_price', 'service_price', 'price'], 'number'],
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
            'cash_price' => 'Cash Price',
            'service_price' => 'Service Price',
            'price' => 'Price',
            'status' => '0 带处理 1通过  2 拒绝',
            'is_price' => '是否打款',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'updated_at' => 'Updated At',
            'cash_type' => '现方式 wechat balance bank',
            'type' => '提现类型',
            'account' => '账户',
            'bank_name' => '开户行',
            'receipt' => '打款凭证',
            'name' => '开户人姓名',
            'is_manual' => '手动打款',
            'bank_code' => 'bank_code',
            'order_no' => 'order_no',
            'reason' => '驳回原因'
        ];
    }

    public static function getOrderNo($user_id)
    {

        return 'TX' . date('YmdHis', time()) . $user_id;
    }
}
