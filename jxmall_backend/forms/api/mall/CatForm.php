<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-13
 * Time: 10:07
 */

namespace app\forms\api\mall;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\models\BaseModel;
use app\models\Cat;
use app\models\Goods;
use app\models\GoodsCat;

class CatForm extends BaseModel
{

    public function search()
    {
        $list = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'parent_id' => 0])->orderBy('sort desc')->asArray()->all();
        foreach ($list as &$item) {
            $second_list = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'parent_id' => $item['id']])->orderBy('sort desc')->asArray()->all();
            foreach ($second_list as &$item1) {
                $third_list = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'parent_id' => $item1['id']])->orderBy('sort desc')->asArray()->all();
                $item1['children'] = $third_list;
            }
            unset($item1);
            $item['children'] = $second_list;
        }
        unset($item);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }

    public function search2()
    {
        $list = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'parent_id' => 0])->orderBy('sort desc')->asArray()->all();
        foreach ($list as &$item) {
            $second_list = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'parent_id' => $item['id']])->orderBy('sort desc')->asArray()->all();
            $item['children'] = $second_list;
        }
        unset($item);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }

    public function searchCatWithGoods()
    {
        $list = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'parent_id' => 0])->orderBy('sort desc')->asArray()->all();
        foreach ($list as &$item) {
            $second_list = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'parent_id' => $item['id']])->orderBy('sort desc')->asArray()->all();
            foreach ($second_list as &$cat) {
                $goods_list = Goods::find()->alias('g')
                    ->andWhere(['g.status' => 1, 'g.is_delete' => 0])
                    ->innerJoin(['gc' => GoodsCat::tableName()], 'gc.goods_id=g.id')
                    ->andWhere(['gc.is_delete' => 0])
                    ->andWhere(['gc.cat_id' => $cat['id']])
                    ->select('g.id,g.name,g.price,g.cover_pic,g.origin_price,g.is_integral,g.use_integral,g.virtual_sales')->limit(10)->orderBy('g.id desc')->asArray()->all();
                if (!count($goods_list)) {
                    $cat['id'] = $item['id'];
                    $cat['name'] = $item['name'];
                    $goods_list = Goods::find()->alias('g')
                        ->andWhere(['g.status' => 1, 'g.is_delete' => 0])
                        ->innerJoin(['gc' => GoodsCat::tableName()], 'gc.goods_id=g.id')
                        ->andWhere(['gc.is_delete' => 0])
                        ->andWhere(['gc.cat_id' => $item['id']])
                        ->select('g.id,g.name,g.price,g.cover_pic,g.origin_price,g.is_integral,g.use_integral,g.virtual_sales')->limit(10)->orderBy('g.id desc')->asArray()->all();
                }
                $cat['list'] = $goods_list;
            }
            unset($cat);
            $item['children'] = $second_list;
        }
        unset($item);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }
}