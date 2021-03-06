<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/5/28
 * Time: 10:17
 * Note:
 */

namespace app\forms\mall\mall;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use app\models\OutsideLink;

class OutsideLinkForm extends BaseModel
{

    public $id;
    public $name;
    public $link;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'link'], 'string']
        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if (!$this->validate()) {

            return $this->responseErrorInfo();
        }

        $link = OutsideLink::getOne($this->id);
        if (!$link) {
            $link = new OutsideLink();
            $link->mall_id = $this->mall_id;
        }
        $link->name = $this->name;
        $link->link = $this->link;
        if (!$link->save()) {
            return $this->responseErrorMsg($link);
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '保存成功');
    }

    public function search()
    {
        $link = OutsideLink::getOne($this->id);
        if (!$link) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '链接不存在');
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['link' => $link->attributes]);
    }
}