<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%level}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $name
 * @property int $level
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property string|null $pic_url
 * @property string|null $detail 说明
 * @property int $enabled
 * @property int $is_auto
 * @property int $is_discount
 * @property int $discount
 * @property int $buy_type 0 支付 1订单完成
 * @property integer $is_inviter 是否具有推广权限
 * @property integer $is_contract_update 升级之前需要签订合同
 * @property string $contract_tpl_id  签署的模板ID
 */
class Level extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%level}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'name', 'level'], 'required'],
            [['discount'], 'number', 'max' => 10],
            [['mall_id','is_inviter','buy_type','is_contract_update', 'is_discount', 'level', 'is_delete', 'created_at', 'deleted_at', 'updated_at', 'enabled', 'is_auto'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['pic_url'], 'string', 'max' => 255],
            [['contract_tpl_id'], 'string'],
            [['detail'], 'string', 'max' => 256],
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
            'name' => 'Name',
            'level' => 'Level',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'pic_url' => 'Pic Url',
            'detail' => '说明',
            'enabled' => '是否启用',
            'is_auto' => '自动升级',
            'is_discount' => '是否折扣',
            'discount' => '折扣',
            'buy_type'=>'0 支付 1订单完成',
            'is_inviter'=>'是否具有推广权限'
        ];
    }
}
