<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-30
 * Time: 10:10
 */

namespace app\plugins\share\forms\mall;


use app\core\ApiCode;
use app\models\BaseModel;
use app\plugins\share\models\ShareLevel;

class LevelListForm extends BaseModel
{

    public function getList()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $list = ShareLevel::find()
            ->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])
            ->orderBy('level ASC')
            ->asArray()
            ->all();
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }

}