<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%express_log}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $express_no
 * @property string $express_name
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $is_delete
 * @property string|null $express_code
 * @property string $name
 * @property string $mobile
 * @property string $address
 * @property int $eorder_detail_id 电子面单详情ID
 * @property int $order_id 订单ID
 * @property string $order_detail_id 订单详情ID
 * @property string $order_type 订单类型
 * @property int $num 商品数量
 * @property int|null $is_all 0 分包 1整包
 * @property string|null $order_no
 * @property int $eorder_id //电子面单模板ID
 */
class ExpressLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%express_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'express_no', 'express_name', 'name', 'mobile', 'address'], 'required'],
            [['mall_id', 'created_at', 'deleted_at', 'updated_at', 'is_delete', 'eorder_detail_id', 'order_id', 'num', 'is_all','eorder_id'], 'integer'],
            [['express_no'], 'string', 'max' => 64],
            [['express_name', 'name','order_no', 'order_type'], 'string', 'max' => 45],
            [['express_code'], 'string', 'max' => 10],
            [['mobile'], 'string', 'max' => 11],
            [['address', 'order_detail_id'], 'string', 'max' => 128],
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
            'express_no' => 'Express No',
            'express_name' => 'Express Name',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
            'express_code' => 'Express Code',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'address' => 'Address',
            'eorder_detail_id' => '电子面单详情ID',
            'order_id' => '订单ID',
            'order_detail_id' => '订单详情ID',
            'order_type' => '订单类型',
            'num' => '商品数量',
            'is_all' => '0 分包 1整包',
            'order_no'=>'订单号',
            'eorder_id'=>'电子面单模板'
        ];
    }
}
