<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-11
 * Time: 17:32
 */

namespace app\forms\api\cart;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\Cart;

class CartForm extends BaseModel
{


    public function getCount()
    {
        $count = Cart::find()->where(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0])->count();
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['count' => $count ?? 0]);
    }


}