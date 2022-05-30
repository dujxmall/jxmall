<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-10
 * Time: 20:50
 */

namespace app\plugins\share\forms\mall;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\User;
use app\plugins\share\models\Share;
use app\plugins\share\models\ShareApply;
use app\plugins\share\models\ShareOrder;
use app\plugins\share\models\SharePriceLog;

class ShareDataForm extends BaseModel
{

    public function search()
    {


        $info['status_0'] = SharePriceLog::find()->where(['status' => 0, 'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])->sum('price');
        $info['status_1'] = SharePriceLog::find()->where(['status' => 1, 'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])->sum('price');
        $info['order'] = ShareOrder::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])->count();
        $info['apply'] = ShareApply::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'status' => 0])->count();
        $info['share'] = Share::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])->count();
        $info['user'] = User::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])->count();


        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['info' => $info]);


    }

}