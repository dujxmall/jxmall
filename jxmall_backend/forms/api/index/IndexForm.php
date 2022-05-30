<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-10
 * Time: 8:10
 */

namespace app\forms\api\index;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\helpers\SerializeHelper;
use app\models\BaseModel;
use app\models\DiyPage;

class IndexForm extends BaseModel
{

    public function getIndex()
    {
        $homeData = OptionHelper::get('diy_home', \Yii::$app->mall->id);
        if ($homeData) {
            $name = OptionHelper::get('diy_home_name', \Yii::$app->mall->id);
            //   return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['page_data' => $homeData, 'name' => OptionHelper::get('diy_home_name', \Yii::$app->mall->id)]);
        }
        if (!$homeData) {
            $homePage = DiyPage::findOne(['is_delete' => 0, 'is_home' => 1, 'mall_id' => $this->mall_id]);
            if (!$homePage) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '未设置主页');
            }
            $name = $homePage->name;
            $homeData = SerializeHelper::decode($homePage->data);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['page_data' => $homeData, 'name' => $name]);
    }

}