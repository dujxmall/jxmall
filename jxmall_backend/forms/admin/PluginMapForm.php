<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2022-05-08
 * Time: 21:59
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use app\models\Plugin;

class PluginMapForm extends BaseModel
{

    public function getList()
    {
        $list = Plugin::find()->andWhere(['is_delete' => 0])->select('name,label')->asArray()->all();
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }

}