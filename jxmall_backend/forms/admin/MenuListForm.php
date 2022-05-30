<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/4
 * Time: 22:09
 * Note:
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\AuthorityHelper;
use app\helpers\ResponseHelper;
use app\models\BaseMenu;
use app\models\BaseModel;
use app\models\Menu;

class MenuListForm extends BaseModel
{
    public function getList()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $plugins = \app\models\Plugin::find()->select('name')->andWhere(['is_delete' => 0])->column();
        $plugins[] = 'mall';
        $list = Menu::find()->orderBy('order_num asc')->andWhere(['plugin'=>$plugins])->asArray()->all();
        return [
            "msg" => "操作成功",
            "code" => 0,
            'data' => $list
        ];
    }
}