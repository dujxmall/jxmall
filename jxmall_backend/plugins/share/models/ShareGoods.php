<?php


namespace app\plugins\share\models;

use app\models\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%plugin_share_goods}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $goods_id
 * @property int $goods_type
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $is_share_goods 是否是分销商品
 * @property int $is_alone 独立设置佣金
 * @property int $price_type
 */
class ShareGoods extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_share_goods}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'goods_id'], 'required'],
            [['mall_id', 'goods_id', 'goods_type', 'is_delete', 'created_at', 'deleted_at', 'updated_at', 'is_share_goods', 'is_alone','price_type'], 'integer'],
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
            'goods_type' => 'Goods Type',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_share_goods' => '是否是分销商品',
            'is_alone' => '独立设置佣金',
            'price_type'=>'佣金类型'
        ];
    }
}
