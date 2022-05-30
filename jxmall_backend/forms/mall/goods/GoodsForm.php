<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 22:29
 */

namespace app\forms\mall\goods;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\helpers\SerializeHelper;
use app\helpers\ServerHelper;
use app\models\AttrGroup;
use app\models\BaseModel;
use app\models\Cat;
use app\models\FreightTpl;
use app\models\Goods;
use app\models\GoodsAttr;
use app\models\GoodsCat;
use app\models\GoodsInfo;
use app\models\GoodsPic;
use yii\helpers\Json;

class GoodsForm extends BaseModel
{
    public $id;
    public $name;
    public $price;
    public $origin_price;
    public $no;
    public $status;
    public $cover_pic;
    public $unit;
    public $is_limit;

    public $weight;
    public $is_attr;
    public $cost_price;
    public $mch_id;
    public $stock;
    public $pic_list;
    public $detail;
    public $attr_list;
    public $limit_area_list;

    public $attr_group_list;
    public $sales;
    public $virtual_sales;
    public $share_title;
    public $sort;
    public $is_recommend;
    public $remarks;
    public $express_type;//快递类型
    public $freight_id; //运费模板ID
    public $freight_price;//运费

    public $is_integral;
    public $integral_price;
    public $use_integral;
    public $use_integral_type;
    public $cat_list;
    public $cat_ids;
    public $permission;
    public $is_negotiable;
    public $subtitle;
    public $is_member_price;
    public $level_price;
    public $limit_num;

    public $total_stock;
    public $warning_stock;

    public function rules()
    {
        return [
            [['price', 'origin_price', 'weight', 'cost_price', 'freight_price', 'integral_price'], 'number'],
            [['status','total_stock','warning_stock', 'limit_num', 'is_member_price', 'is_limit', 'use_integral', 'is_attr', 'mch_id', 'stock', 'id', 'sales', 'virtual_sales', 'sort',
                'is_recommend', 'express_type', 'freight_id', 'use_integral', 'use_integral_type', 'is_integral', 'is_negotiable'], 'integer'],
            [['name', 'no', 'share_title'], 'string', 'max' => 255],
            [['cover_pic', 'subtitle'], 'string', 'max' => 255],
            [['remarks'], 'string', 'max' => 128],
            [['detail'], 'string'],
            [['attr_list', 'attr_group_list', 'limit_area_list', 'pic_list', 'cat_list', 'cat_ids', 'permission', 'level_price'], 'safe'],
            [['unit'], 'string', 'max' => 10],
            [['mch_id', 'is_attr', 'use_integral', 'is_limit', 'status', 'weight'], 'default', 'value' => 0],
            [['unit'], 'default', 'value' => '件'],
            [['pic_list', 'detail', 'attr_list', 'attr_group_list', 'limit_area_list'], 'trim'],
            [['limit_area_list', 'detail', 'attr_list', 'pic_list', 'attr_group_list'], 'default', 'value' => null]
        ]; // TODO: Change the autogenerated stub
    }


    public function attributeLabels()
    {
        return [
            'cover_pic' => '缩略图',
            'name' => '名称',
            'is_limit' => '是否开启限购',
            'pic_list' => '商品轮播图',
            'is_recommend' => '是否推荐',
            'remarks' => '备注',
            'is_integral' => '开启积分抵扣',
            'integral_price' => '积分抵扣额度',
            'use_integral_type' => '积分抵扣类型',
            'use_integral' => '使用积分',
            'is_negotiable' => '是否开启面议',
            'limit_num' => '限购数量'
        ]; // TODO: Change the autogenerated stub
    }


    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $goods = null;
        if (!$this->cover_pic) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '缩略图不能为空');
        }
        if (!$this->name) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '商品名称不能为空');
        }
        if ($this->id) {
            $goods = Goods::findOne(['is_delete' => 0, 'id' => $this->id]);
            if (!$goods) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '商品不存在或已被删除!');
            }
        }
        if (!$this->is_limit) {
            $this->is_limit = 0;
        }
        if (!$this->limit_num) {
            $this->limit_num = 0;
        }
        if ($this->is_negotiable) {
            if (!$this->price) {
                $this->price = 0;
            }
        }

        if ($this->price < 0) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '价格不能小于0');
        }
        if (!$this->origin_price) {
            $this->origin_price = 0;
        }
        if (!$this->cost_price) {
            $this->cost_price = 0;
        }
        $t = \Yii::$app->db->beginTransaction();
        if (!$goods) {
            $goods = new Goods();
            $goods->mall_id = $this->mall_id;
        }
        $goods->attributes = $this->attributes;
        if (!$goods->save()) {
            $t->rollBack();
            return $this->responseErrorMsg($goods);
        }
        if (!$goods->is_attr) {
            Goods::setStock($goods->id, $this->stock);
        }

        $goodsInfo = GoodsInfo::findOne(['goods_id' => $goods->id, 'is_delete' => 0, 'mall_id' => $goods->mall_id]);
        if (!$goodsInfo) {
            $goodsInfo = new GoodsInfo();
            $goodsInfo->mall_id = $goods->mall_id;
            $goodsInfo->goods_id = $goods->id;
        }
        if ($this->pic_list) {
            $goodsInfo->pic_list = Json::encode($this->pic_list);
        } else {
            $goodsInfo->pic_list = '';
        }
        $goodsInfo->detail = $this->detail;
        $goodsInfo->limit_area_list = $this->limit_area_list;
        if ($this->permission) {
            $goodsInfo->permission = Json::encode($this->permission);
        }
        if ($this->remarks) {
            $goodsInfo->remarks = $this->remarks;
        }
        if ($this->is_member_price) {
            $goodsInfo->level_price = Json::encode($this->level_price);
        }
        if (!$goodsInfo->save()) {
            $t->rollBack();
            return $this->responseErrorMsg($goodsInfo);
        }
        if ($this->cat_ids) {
            GoodsCat::deleteAll(['goods_id' => $goods->id]);
            foreach ($this->cat_ids as $item) {
                $cat = Cat::findOne(['id' => $item, 'is_delete' => 0]);
                if ($cat) {
                    //重建goods_cat
                    $goods_cat = GoodsCat::findOne(['cat_id' => $item, 'is_delete' => 0, 'goods_id' => $goods->id]);
                    if (!$goods_cat) {
                        $goods_cat = new GoodsCat();
                        $goods_cat->level = $cat->type;
                        $goods_cat->goods_id = $goods->id;
                        $goods_cat->mall_id = $goods->mall_id;
                    }
                    $goods_cat->cat_id = $item;
                    if (!$goods_cat->save()) {
                        $t->rollBack();
                        return $this->responseErrorMsg($goods_cat);
                    }
                }
            }
        }
        $goods->is_integral = $this->is_integral;
        if ($this->is_integral) {
            if ($this->use_integral_type == 1) {
                $goods->integral_price = 1;
            } else {
                //固定金额兑换 需要判断兑换的积分是否小于或等于售价
                $goods->integral_price = $this->integral_price;
                if ($goods->integral_price > $goods->price) {
                    $t->rollBack();
                    return ResponseHelper::send(ApiCode::CODE_FAIL, '兑换金额应小于或等于商品价格');
                }
            }
            $goods->use_integral_type = $this->use_integral_type;
            $goods->use_integral = $this->use_integral;

        }
        if ($this->is_attr) {
            $res = $this->setAttr($goods->id);
            if ($res['code'] == 1) {
                $t->rollBack();
                return $res;
            }
            $goods->stock = $res['data']['total_stock'];
            $goods->save();
            Goods::setStock($goods->id, $goods->stock);
        }

        $t->commit();
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '保存成功');
    }

    private function setAttr($goods_id)
    {
        if (!$this->attr_group_list) {
            return ['code' => 1, 'msg' => '请添加规格组'];
        }
        if (!$this->attr_list) {
            return ['code' => 1, 'msg' => '请添加规格参数'];
        }
        $goods_total_stock = 0;
        $attr_group_list = $this->attr_group_list;
        $group_ids = [];
        foreach ($attr_group_list as $attr_group) {
            $group = AttrGroup::findOne(['goods_id' => $goods_id, 'mall_id' => $this->mall_id, 'is_delete' => 0, 'attr_group_name' => $attr_group['attr_group_name'], 'attr_group_id' => $attr_group['attr_group_id']]);
            if (!$group) {
                $group = new AttrGroup();
                $group->attr_group_name = $attr_group['attr_group_name'];
                $group->attr_group_id = $attr_group['attr_group_id'];
                $group->goods_id = $goods_id;
                $group->mall_id = $this->mall_id;
            }
            $group->attr_list = Json::encode($attr_group['attr_list']);
            if (!$group->save()) {
                return ['code' => 1, 'msg' => '保存失败', 'data' => ['error' => $group->getErrors()]];
            }
            $group_ids[] = $group->id;
        }

        $del_group_ids = AttrGroup::find()->where(['goods_id' => $goods_id, 'mall_id' => $this->mall_id, 'is_delete' => 0])->andWhere(['not in', 'id', $group_ids])->select('id')->column();

        AttrGroup::deleteAll(['id' => $del_group_ids]); //删掉多余的数据

        unset($attr_group);
        $goods_attr_list = $this->attr_list;
        $goods_attr_ids = [];
        foreach ($goods_attr_list as $attr) {
            $goods_attr = GoodsAttr::findOne(['goods_id' => $goods_id, 'is_delete' => 0, 'attr_list' => Json::encode($attr['attr_list'])]);
            if (!$goods_attr) {
                $goods_attr = new GoodsAttr();
                $goods_attr->attr_list = Json::encode($attr['attr_list']);
                $goods_attr->goods_id = $goods_id;
                $goods_attr->mall_id = $this->mall_id;
            }
            $goods_attr->stock = $attr['stock'];
            $goods_attr->price = $attr['price'];
            $goods_attr->no = $attr['no'];
            $goods_attr->weight = $attr['weight'];
            $goods_attr->pic_url = $attr['pic_url'];
            $goods_total_stock += $attr['stock'];
            if (!$goods_attr->save()) {
                return ['code' => 1, 'msg' => '保存失败', 'data' => ['error' => $goods_attr->getErrors()]];
            }

            $goods_attr_ids[] = $goods_attr->id;
        }

        $del_goods_attr_ids = GoodsAttr::find()->where(['goods_id' => $goods_id, 'mall_id' => $this->mall_id, 'is_delete' => 0])->andWhere(['not in', 'id', $goods_attr_ids])->select('id')->column();

        GoodsAttr::deleteAll(['id' => $del_goods_attr_ids]); //删掉多余的数据
        unset($attr);
        return ['code' => 0, 'msg' => '保存成功', 'data' => ['total_stock' => $goods_total_stock ?? 0]];
    }

    public function search()
    {
        $info = [];
        $goods = Goods::findOne(['is_delete' => 0, 'id' => $this->id]);
        if (!$goods) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '商品不存在或已被删除!');
        }
        $info = $goods->attributes;
        $goodsInfo = GoodsInfo::findOne(['is_delete' => 0, 'goods_id' => $goods->id, 'mall_id' => $this->mall_id]);
        $info['pic_list'] = [];
        $info['limit_area_list'] = [];
        if ($goodsInfo) {
            $info['detail'] = $goodsInfo->detail;
            if ($goodsInfo->pic_list && $goodsInfo->pic_list != 'null') {
                $info['pic_list'] = Json::decode($goodsInfo->pic_list);
            }
            if ($goodsInfo->limit_area_list) {
                $info['limit_area_list'] = Json::decode($goodsInfo->limit_area_list);
            }
        }
        $attr_group_list = AttrGroup::find()
            ->where(['goods_id' => $goods->id, 'is_delete' => 0])
            ->orderBy('attr_group_id ASC')
            ->select('attr_group_id,attr_group_name,attr_list')
            ->asArray()
            ->all();
        /**
         * @var AttrGroup $attr_group_list []
         */
        foreach ($attr_group_list as &$item) {
            $item['attr_list'] = Json::decode($item['attr_list']);
        }
        unset($item);
        $info['attr_group_list'] = $attr_group_list ?? [];
        $goods_attr_list = GoodsAttr::find()
            ->where(['goods_id' => $goods->id, 'is_delete' => 0])
            ->orderBy('id ASC')
            ->select('stock,no,price,pic_url,weight,attr_list,id')
            ->asArray()
            ->all();
        foreach ($goods_attr_list as &$item) {
            $item['attr_list'] = Json::decode($item['attr_list']);
        }
        unset($item);
        $info['attr_list'] = $goods_attr_list ?? [];
        $cat_list = $goods->getCatList();
        $cat_ids = [];
        foreach ($cat_list as $item) {
            $cat_ids[] = $item['id'];
        }
        $info['cat_ids'] = $cat_ids;
        $info['cat_list'] = $cat_list;
        if ($goods->express_type == 2) {
            $freight = FreightTpl::findOne(['is_delete' => 0, 'id' => $goods->freight_id, 'enabled' => 1]);
            $info['freight'] = $freight;
        }
        if ($goodsInfo->level_price) {
            $info['level_price'] = Json::decode($goodsInfo->level_price);
        } else {
            $info['level_price'] = [];
        }
        $info['permission'] = Json::decode($goodsInfo->permission);
        if (isset($info['permission']['explode_is_level'])) {
            $info['permission']['explode_is_level'] = intval($info['permission']['explode_is_level']);
        }
        if (isset($info['permission']['buy_is_level'])) {
            $info['permission']['buy_is_level'] = intval($info['permission']['buy_is_level']);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['info' => $info]);
    }

    public function delete()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $goods = Goods::findOne(['id' => $this->id, 'is_delete' => 0, 'mall_id' => $this->mall_id]);
        if (!$goods) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '商品不存在或已被删除');
        }
        $goods->is_delete = 1;
        if (!$goods->save()) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '删除失败', ['error' => $goods->getErrors()]);
        }
        Goods::delStock($goods->id);
        $ids = GoodsAttr::find()->andWhere(['goods_id' => $goods->id, 'is_delete' => 0])->select('id')->column();
        if ($ids) {
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    Goods::delStock($goods->id, $id);
                }

            } else {
                Goods::delStock($goods->id, $ids);
            }
        }

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '删除成功');
    }

    public function statusChange()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $goods = Goods::findOne(['id' => $this->id, 'is_delete' => 0, 'mall_id' => $this->mall_id]);
        if (!$goods) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '商品不存在或已被删除');
        }
        if ($this->status == 1) {
            if ($goods->stock <= 0) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '库存不足，上架失败');
            }
        }
        $goods->status = $this->status;
        if (!$goods->save()) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '上架失败', ['error' => $goods->getErrors()]);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '上架成功');
    }

    public function recommendChange()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $goods = Goods::findOne(['id' => $this->id, 'is_delete' => 0, 'mall_id' => $this->mall_id]);
        if (!$goods) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '商品不存在或已被删除');
        }
        $goods->is_recommend = $this->is_recommend;
        if (!$goods->save()) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '操作失败', ['error' => $goods->getErrors()]);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '操作成功');
    }
}