<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-18
 * Time: 14:29
 */

namespace app\forms\mall\user;


use app\core\ApiCode;
use app\models\BaseModel;

class DefaultLevelListForm extends BaseModel
{
    public function getList()
    {
        $list = [];
        for ($i = 1; $i <= 100; $i++) {
            array_push($list, ['name' => '等级' . $i, 'level' => $i]);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }

}
