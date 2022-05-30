<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-31
 * Time: 0:46
 */

namespace app\plugins\share\forms\mall;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\models\BaseModel;
use app\models\Goods;
use app\plugins\share\models\ShareGoods;

class ShareGoodsForm extends BaseModel
{

    public $limit;
    public $page;
    public $keyword;
    public $id;
    public $is_share_goods;


    public function rules()
    {
        return [
            [['page', 'limit', 'is_share_goods', 'id'], 'integer']
        ]; // TODO: Change the autogenerated stub
    }

    public function getList()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $query = ShareGoods::find()->alias('sg')
            ->leftJoin(['g' => Goods::tableName()], 'g.id=sg.goods_id')
            ->andWhere(['sg.mall_id' => \Yii::$app->mall->id, 'sg.is_delete' => 0]);
        if ($this->keyword) {
            $query->andWhere(['like', 'g.name', $this->keyword]);
        }
        $list = $query->page($pagination, $this->limit, $this->page)->select('g.*,g.id as goods_id,sg.is_share_goods,sg.id as share_goods_id')->orderBy('sg.updated_at DESC')->asArray()->all();
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);
    }


    public function syncGoods()
    {
        $list = Goods::find()->where(['is_delete' => 0, 'mall_id' => \Yii::$app->mall->id])->all();
        $i = 0;
        /**
         * @var  Goods $goods
         */
        //导入
        foreach ($list as $goods) {
            $exists = ShareGoods::find()->where(['goods_id' => $goods->id, 'mall_id' => $goods->mall_id, 'is_delete' => 0])->exists();
            if (!$exists) {
                $i++;
                $shareGoods = new ShareGoods();
                $shareGoods->mall_id = $goods->mall_id;
                $shareGoods->goods_id = $goods->id;
                $shareGoods->is_share_goods = 1;
                $shareGoods->goods_type = 0;
                $shareGoods->save();
            }
        }
        $list = ShareGoods::find()->where(['mall_id' => $goods->mall_id, 'is_delete' => 0])->all();
        //删除

        /**
         * @var  ShareGoods $goods
         */
        foreach ($list as $goods) {
            $exists = Goods::find()->where(['id' => $goods->goods_id, 'mall_id' => $goods->mall_id, 'is_delete' => 0])->exists();
            if (!$exists) {
                OptionHelper::remove('goods_share_price_' . $goods->id, \Yii::$app->mall->id);
                $goods->is_delete = 1;
                $goods->save();
            }
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '同步成功，完成' . $i . '个商品');
    }


    public function shareStatus()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $shareGoods = ShareGoods::findOne(['is_delete' => 0, 'id' => $this->id]);
        if (!$shareGoods) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '分销商品不存在');
        }
        $shareGoods->is_share_goods = $this->is_share_goods;
        if (!$shareGoods->save()) {
            return $this->responseErrorMsg($shareGoods);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '保存成功');
    }


}