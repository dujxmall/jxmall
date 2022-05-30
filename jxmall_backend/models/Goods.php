<?php

namespace app\models;

use app\helpers\CacheHelper;
use app\helpers\SerializeHelper;
use Yii;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property int $id
 * @property string $name 商品名
 * @property float $price 商品价格
 * @property float $origin_price 商品原价
 * @property string|null $no 商品编号
 * @property int $status 状态  1上架 0下
 * @property string $cover_pic 缩略图
 * @property string $unit 单位
 * @property int $is_limit 是否开启会员限购
 * @property float $weight 重量
 * @property int $is_delete
 * @property int $deleted_at
 * @property int $updated_at
 * @property int $created_at
 * @property float $cost_price 原价
 * @property int $mall_id
 * @property int $is_attr 是否使用多规格
 * @property int $mch_id 商户ID
 * @property int|null $stock 库存
 * @property int $is_area_limit 开启区域限购
 * @property int $sales 销量
 * @property int $virtual_sales 虚拟销量
 * @property string $share_title
 * @property int $sort
 * @property int $is_recommend
 * @property int $freight_id
 * @property int $express_type
 * @property float $freight_price
 * @property GoodsInfo $info
 * @property int $use_integral_type 0固定抵扣 1按比例抵扣
 * @property int $use_integral 多少积分
 * @property float $integral_price  抵扣金额
 * @property int $is_integral 是否开启积分抵扣
 * @property int $is_negotiable 是否开启面议
 * @property string $subtitle 商品小标题
 * @property integer $limit_num 限购数量
 * @property integer $is_member_price  会员价
 * @property integer $total_stock
 * @property integer $warning_stock
 */
class Goods extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'cover_pic', 'deleted_at', 'updated_at', 'created_at', 'mall_id'], 'required'],
            [['price', 'origin_price', 'weight', 'cost_price', 'freight_price', 'integral_price'], 'number'],
            [['status', 'total_stock', 'warning_stock', 'is_member_price', 'limit_num', 'use_integral_type', 'use_integral', 'is_negotiable', 'is_integral', 'is_recommend', 'freight_id', 'express_type', 'is_limit', 'is_delete', 'deleted_at', 'updated_at', 'created_at', 'mall_id', 'is_attr', 'mch_id', 'stock', 'is_area_limit', 'sales', 'virtual_sales', 'sort'], 'integer'],
            [['name', 'no', 'share_title'], 'string', 'max' => 255],
            [['cover_pic', 'subtitle'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名',
            'price' => '商品价格',
            'origin_price' => '商品原价',
            'no' => '商品编号',
            'status' => '状态  1上架 0下',
            'cover_pic' => '缩略图',
            'unit' => '单位',
            'is_limit' => '是否开启区域限购',
            'weight' => '重量',
            'is_delete' => 'Is Delete',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'cost_price' => '原价',
            'mall_id' => 'Mall ID',
            'is_attr' => '是否使用多规格',
            'mch_id' => '商户ID',
            'stock' => '库存',
            'is_area_limit' => '开启区域限购',
            'sales' => '销量',
            'share_title' => '分享标题',
            'sort' => '排序',
            'is_recommend' => '是否推荐',
            'express_type' => '快递类型',
            'freight_price' => '运费',
            'freight_id' => '运费模板',
            'integral_price' => '抵扣金额',
            'use_integral' => '使用积分',
            'is_integral' => '使用积分抵扣',
            'use_integral_type' => '0固定抵扣 1按比例抵扣',
            'is_negotiable' => '是否开启面议',
            'subtitle' => '商品小标题',
            'limit_num' => '限购数量',
            'is_member_price' => '是否启用会员价',
            'warning_stock'=>'预警库存',
            'total_stock'=>'总库存'
        ];
    }

    public function getCatList()
    {
        $list = GoodsCat::find()->alias('gc')
            ->where(['gc.is_delete' => 0, 'gc.goods_id' => $this->id])
            ->leftJoin(['c' => Cat::tableName()], 'c.id=gc.cat_id')
            ->select('gc.cat_id as id,c.name')
            ->orderBy('gc.level ASC')
            ->asArray()
            ->all();
        return $list;
    }

    public function getAttrList()
    {
        return $this->hasMany(GoodsAttr::class, ['goods_id' => 'id'])->select('id,price,attr_list,stock,weight,pic_url')->asArray()->all();
    }

    public function getAttrGroupList()
    {
        return $this->hasMany(AttrGroup::class, ['goods_id' => 'id'])->select('id,attr_group_id,attr_group_name,attr_list')->asArray()->all();
    }

    public function getInfo()
    {
        return $this->hasOne(GoodsInfo::class, ['goods_id' => 'id']);
    }

    public static function getBaseInfo($id)
    {
        $goods = CacheHelper::get('mall_goods_base_' . $id);
        if ($goods) {
            return $goods;
        }
        $goods = self::find()->where(['is_delete' => 0, 'id' => $id])->select('id,cover_pic,name,price')->one();
        if (!$goods) {
            return null;
        }
        CacheHelper::set('mall_goods_base_' . $id, $goods);
        return $goods;
    }


    public function getLevelPrice($level_id = null)
    {
        $model = Level::getOne($level_id);
        if (!$model || !$model->is_discount) {
            return $this->price;
        }
        $goods_info = $this->info;
        if ($this->is_member_price) {
            if ($goods_info->level_price) {
                $level_price = SerializeHelper::decode($goods_info->level_price);
                foreach ($level_price as $item) {
                    if ($model->level == $item['level']) {
                        return $item['price'];
                    }
                }
            }
            if ($model && $model->is_discount) {
                if ($model->discount) {
                    $price = $this->price * $model->discount;
                    if ($price < 0) {
                        return '0.00';
                    }
                    return $price;
                }
            }
        }
        return $this->price;
    }

    public static function getStock($goods_id, $attr_id = 0)
    {
        $key = 'stock_goods_' . $goods_id . '_' . $attr_id;
        $num = Yii::$app->redis->get(CacheHelper::getKeyName($key));
        return $num ?? 0;
    }

    public static function setStock($goods_id, $num, $attr_id = 0)
    {
        $key = 'stock_goods_' . $goods_id . '_' . $attr_id;
        Yii::$app->redis->set(CacheHelper::getKeyName($key), $num);
    }

    public static function decStock($goods_id, $num = 0, $attr_id = 0)
    {
        $key = 'stock_goods_' . $goods_id . '_' . $attr_id;
        Yii::$app->redis->decrby(CacheHelper::getKeyName($key), $num);
        $total = self::getStock($goods_id, $attr_id);
        if (!$attr_id) {
            Goods::updateAll(['stock' => $total], ['id' => $goods_id]);
        } else {
            GoodsAttr::updateAll(['stock' => $total], ['id' => $attr_id]);
            $attr_id = 0;
            $key = 'stock_goods_' . $goods_id . '_' . $attr_id;
            Yii::$app->redis->decrby(CacheHelper::getKeyName($key), $num);
            $total = Yii::$app->redis->get(CacheHelper::getKeyName($key));
            Goods::updateAll(['stock' => $total], ['id' => $goods_id]);
        }
    }

    public static function incrStock($goods_id, $num = 0, $attr_id = 0)
    {
        $key = 'stock_goods_' . $goods_id . '_' . $attr_id;
        Yii::$app->redis->incrby(CacheHelper::getKeyName($key), $num);
        $total = self::getStock($goods_id);
        if (!$attr_id) {
            Goods::updateAll(['stock' => $total], ['id' => $goods_id]);
        } else {
            GoodsAttr::updateAll(['stock' => $total], ['id' => $attr_id]);
            $attr_id = 0;
            $key = 'stock_goods_' . $goods_id . '_' . $attr_id;
            Yii::$app->redis->incrby(CacheHelper::getKeyName($key), $num);
            $total = Yii::$app->redis->get(CacheHelper::getKeyName($key));
            Goods::updateAll(['stock' => $total], ['id' => $goods_id]);
        }
    }

    public static function delStock($goods_id, $attr_id = 0)
    {
        $key = 'stock_goods_' . $goods_id . '_' . $attr_id;
        Yii::$app->redis->del(CacheHelper::getKeyName($key));
    }

}
