<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/22
 * Time: 13:56
 * Note:
 */

namespace app\plugins\integral\forms\api;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\helpers\UserHelper;
use app\models\BaseModel;
use app\models\User;
use app\plugins\integral\models\IntegralSignLog;


class IntegralSignLogListForm extends BaseModel
{

    public $month;

    public function rules()
    {
        return [[['month'], 'string']]; // TODO: Change the autogenerated stub
    }


    public function getList()
    {
        $query = IntegralSignLog::find()->andWhere(['is_delete' => 0, 'user_id' => UserHelper::getMyId()]);
        $list = $query->page($this->pagination, $this->limit, $this->page)->asArray()->all();
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $this->pagination]);
    }

    public function getListByMonth()
    {
        $query = IntegralSignLog::find()->andWhere(['is_delete' => 0, 'user_id' => UserHelper::getMyId()]);
        $list = $query->andWhere(['like', 'date', $this->month])
            ->asArray()
            ->all();
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }

}