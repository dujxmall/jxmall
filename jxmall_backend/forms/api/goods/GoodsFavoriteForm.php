<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-13
 * Time: 16:26
 */

namespace app\forms\api\goods;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\GoodsFavorite;

class GoodsFavoriteForm extends BaseModel
{

    public $goods_id;


    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id'], 'integer']
        ]; // TODO: Change the autogenerated stub
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-13
     * @Time: 16:28
     * @Note:状态改变
     */
    public function save()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $favorite = GoodsFavorite::findOne(['goods_id' => $this->goods_id, 'user_id' => \Yii::$app->user->identity->id]);
        if ($favorite) {
            if ($favorite->is_delete == 1) {
                $favorite->is_delete = 0;
            } else {
                $favorite->is_delete = 1;
            }
        } else {
            $favorite = new GoodsFavorite();
            $favorite->mall_id = \Yii::$app->mall->id;
            $favorite->goods_id = $this->goods_id;
            $favorite->user_id = \Yii::$app->user->identity->id;
        }

        if (!$favorite->save()) {
            return $this->responseErrorMsg($favorite);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '操作成功');
    }
}