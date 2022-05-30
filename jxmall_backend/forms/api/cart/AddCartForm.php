<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-11
 * Time: 16:33
 */

namespace app\forms\api\cart;

use app\core\ApiCode;
use app\models\BaseModel;
use app\models\Cart;

class AddCartForm extends BaseModel
{

    public $goods_id;
    public $goods_attr_id;
    public $num;

    public function rules()
    {
        return [
            [['goods_id', 'goods_attr_id', 'num'], 'integer'],
            [['goods_id', 'goods_attr_id', 'num'], 'required'],
        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        $cart = Cart::findOne(['is_delete' => 0, 'user_id' => \Yii::$app->user->identity->id, 'goods_id' => $this->goods_id, 'goods_attr_id' => $this->goods_attr_id]);
        if (!$cart) {
            $cart = new Cart();
            $cart->attributes = $this->attributes;
            $cart->user_id = \Yii::$app->user->identity->id;
            $cart->mall_id = \Yii::$app->mall->id;
        } else {
            $cart->num += $this->num;
        }

        if (!$cart->save()) {
            return $this->responseErrorMsg($cart);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '加入成功');
    }


}