<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-27
 * Time: 10:20
 */

namespace app\forms\mall\goods;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\Cat;
use app\models\Goods;

class CatLevelForm extends BaseModel
{

    public $cat_id;

    public function rules()
    {
        return [
            [['cat_id'], 'integer'],
        ]; // TODO: Change the autogenerated stub
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-22
     * @Time: 16:46
     * @Note:给分类用的
     * @return array
     */

    public function getList()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $query = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'type' => 0]);
        if ($this->cat_id) {
            $query->andWhere(['!=', 'id', $this->cat_id]);
        }

        $list = $query->orderBy('created_at DESC')->asArray()->all();
        foreach ($list as &$item) {
            $query = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'type' => 1, 'parent_id' => $item['id']]);
            if ($this->cat_id) {
                $query->andWhere(['!=', 'id', $this->cat_id]);
            }
            $children = $query->orderBy('created_at DESC')->asArray()->all();
            $item['children'] = $children;
        }
        unset($item);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-22
     * @Time: 16:46
     * @Note:给商品用的
     * @return array
     */
    public function getAllList()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $list = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'type' => 0])->orderBy('created_at DESC')->asArray()->all();

        foreach ($list as &$item) {
            $children = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'type' => 1, 'parent_id' => $item['id']])->orderBy('created_at DESC')->asArray()->all();
            foreach ($children as &$child1) {
                $children1 = Cat::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'type' => 2, 'parent_id' => $child1['id']])->orderBy('created_at DESC')->asArray()->all();
                $child1['children'] = $children1;
            }
            unset($child1);
            $item['children'] = $children;
        }
        unset($item);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }

}