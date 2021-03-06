<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-23
 * Time: 11:04
 */

namespace app\forms\common\file;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\FileGroup;

class FileGroupEditForm extends BaseModel
{

    public $id;
    public $name;

    public $is_site;


    public function rules()
    {
        return [
            [['id', 'is_site'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string']
        ]; // TODO: Change the autogenerated stub
    }


    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $group = FileGroup::findOne(['is_delete' => 0, 'id' => $this->id]);
        if (!$group) {
            $group = new FileGroup();
        }
        $group->name = $this->name;
        if ($this->is_site) {
            $group->is_site = $this->is_site;
        }
        $group->admin_id = \Yii::$app->admin->identity->id;
        if(\Yii::$app->mall){
            $group->mall_id = \Yii::$app->mall->id;
        }
        if (!$group->save()) {
            return $this->responseErrorMsg($group);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '保存成功', ['group' => $group]);

    }

}