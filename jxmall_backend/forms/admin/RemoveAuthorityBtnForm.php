<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/4
 * Time: 23:00
 * Note:
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\models\AuthorityMenus;
use app\models\BaseMenu;
use app\models\BaseModel;
use app\models\MenuButton;

class RemoveAuthorityBtnForm extends BaseModel
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required']
        ]; // TODO: Change the autogenerated stub
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $button = MenuButton::getOne($this->id);
        if (!$button) {
            return ResponseHelper::send(ApiCode::CODE_SUCCESS, '');
        }

        $authority = AuthorityMenus::findOne(['base_menu_id' => $button->base_menu_id, 'authority_id' => \Yii::$app->admin->identity->authority_id]);
        if (!$authority) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '没有权限');
        }

        return ResponseHelper::send(ApiCode::CODE_SUCCESS,'可以删除');


    }


}