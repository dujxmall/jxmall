<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/4
 * Time: 20:01
 * Note:
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\models\Api;
use app\models\RoleApi;
use app\models\BaseModel;

class AuthorityApiForm extends BaseModel
{
    public $authority_id;

    public function rules()
    {
        return [
            [['authority_id'], 'string']
        ]; // TODO: Change the autogenerated stub
    }

    public function getList()
    {

        $list = RoleApi::find()->alias('a')->innerJoin(['api' => Api::tableName()], 'a.uniqueid=api.uniqueid')
            ->andWhere(['a.authority_id' => $this->authority_id, 'a.is_delete' => 0, 'api.is_delete' => 0])->asArray()->all();


        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['paths' => $list]);


    }

}