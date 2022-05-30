<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods_info}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $goods_id 对应的商品ID
 * @property string $attr_list 规格
 * @property string $detail 商品详情
 * @property int $deleted_at
 * @property int $updated_at
 * @property int $created_at
 * @property int $is_delete
 * @property string|null $pic_list
 * @property string|null $limit_area_list 限制购买的区域
 * @property string|null $remarks 备注
 * @property string|null $permission 权限
 * @property string $level_price
 */
class GoodsInfo extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'goods_id', 'deleted_at', 'updated_at', 'created_at'], 'required'],
            [['mall_id', 'goods_id', 'deleted_at', 'updated_at', 'created_at', 'is_delete'], 'integer'],
            [['attr_list','level_price', 'detail', 'limit_area_list', 'pic_list', 'remarks', 'permission'], 'string'],
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
            'goods_id' => '对应的商品ID',
            'attr_list' => '规格',
            'detail' => '商品详情',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'is_delete' => 'Is Delete',
            'pic_list' => '商品图片',
            'limit_area_list' => '限制购买的区域',
            'remarks' => '备注',
            'permission' => '权限',
            'level_price'=>'会员价设置'
        ];
    }
}
