<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-21
 * Time: 13:08
 */

namespace app\forms\mall\mall;


use app\core\ApiCode;
use app\helpers\SerializeHelper;
use app\models\BaseModel;
use app\models\FreightPrice;
use app\models\FreightTpl;

class FreightForm extends BaseModel
{

    public $id;
    public $enabled;
    public $name;
    public $is_default;
    public $price_type;
    public $price_list;

    public function rules()
    {
        return [
            [['enabled', 'is_default', 'price_type', 'id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['price_list'], 'safe']
        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $freight = null;

        $t = \Yii::$app->db->beginTransaction();
        if ($this->is_default) {
            $freight = FreightTpl::updateAll(['is_default' => 0], ['is_delete' => 0, 'mall_id' => \Yii::$app->mall->id]);
        }

        if ($this->id) {
            $freight = FreightTpl::findOne(['is_delete' => 0, 'id' => $this->id, 'mall_id' => \Yii::$app->mall->id]);
            if (!$freight) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '模板不存在！');
            }
        }
        if (!$freight) {
            $freight = new FreightTpl();
            $freight->mall_id = \Yii::$app->mall->id;
        } else {
            FreightPrice::deleteAll(['is_delete' => 0, 'mall_id' => \Yii::$app->mall->id, 'tpl_id' => $freight->id]);
        }
        $freight->attributes = $this->attributes;
        if (!$freight->save()) {
            return $this->responseErrorMsg($freight);
        }
        foreach ($this->price_list as $item) {
            $price = new FreightPrice();
            $price->mall_id = $freight->mall_id;
            $price->tpl_id = $freight->id;
            $price->send_area = $item['send_area'];
            $price->send_codes = SerializeHelper::encode($item['send_codes']);
            $price->first_num = $item['first_num'];
            $price->first_price = $item['first_price'];
            $price->other_num = $item['other_num'];
            $price->other_price = $item['other_price'];
            $price->is_union = $item['is_union'];
            if (!$price->save()) {

                $t->rollBack();
                return $this->responseErrorMsg($price);
            }
        }

        $t->commit();
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '保存成功');
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $freight = null;
        if ($this->id) {
            $freight = FreightTpl::findOne(['is_delete' => 0, 'id' => $this->id, 'mall_id' => \Yii::$app->mall->id]);
            if (!$freight) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '模板不存在！');
            }
        }
        $price_list = FreightPrice::find()->where(['tpl_id' => $this->id, 'is_delete' => 0])->select('first_price,first_num,other_price,other_num,send_area,send_codes,is_union')->asArray()->all();
        foreach ($price_list as &$item) {
            $item['send_codes'] = SerializeHelper::decode($item['send_codes']);
        }
        unset($item);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['freight' => $freight, 'price_list' => $price_list]);
    }

    public function delete()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $freight = FreightTpl::findOne(['is_delete' => 0, 'id' => $this->id, 'mall_id' => \Yii::$app->mall->id]);
        if (!$freight) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '模板不存在！');
        }
        $freight->is_delete = 1;

        if (!$freight->save()) {
            return $this->responseErrorMsg($freight);
        }

        FreightPrice::deleteAll(['is_delete' => 0, 'mall_id' => \Yii::$app->mall->id, 'tpl_id' => $freight->id]);

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '删除成功！');
    }
}