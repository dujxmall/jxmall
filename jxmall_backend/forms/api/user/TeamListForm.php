<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-01-05
 * Time: 17:54
 */

namespace app\forms\api\user;


use app\core\ApiCode;
use app\helpers\DateHelper;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use app\models\CommonOrder;
use app\models\User;
use app\models\UserParent;

class TeamListForm extends BaseModel
{

    public $page;
    public $limit = 10;

    public function rules()
    {
        return [
            [['page', 'limit'], 'integer']
        ]; // TODO: Change the autogenerated stub
    }


    public function getList()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        $list = UserParent::find()
            ->alias('up')
            ->leftJoin(['u' => User::tableName()], 'u.id=up.user_id')
            ->where(['up.parent_id' => \Yii::$app->user->identity->id, 'up.is_delete' => 0])
            ->orderBy('up.level asc')
            ->select('u.id,u.avatar_url,u.nickname,u.total_price,u.price,u.created_at')
            ->page($pagination, $this->limit, $this->page)->asArray()->all();

        foreach ($list as &$item) {
            $teamCount = UserParent::find()->where(['parent_id' => $item['id'], 'is_delete' => 0])->count();
            $item['count'] = $teamCount ?? 0;
            $total_price = CommonOrder::find()->where(['user_id' => $item['id'], 'status' => 1])->sum('pay_price');
            $item['self_price'] = $total_price ?? 0;
            $team_price = UserParent::find()
                ->leftJoin(['o' => CommonOrder::tableName()], 'o.user_id=up.user_id')
                ->alias('up')
                ->where(['up.parent_id' => $item['id'], 'up.is_delete' => 0, 'o.status' => 1])
                ->sum('pay_price');
            $item['team_price'] = $team_price ?? 0;
            $item['created_at']=DateHelper::format($item['created_at']);

        }
        unset($item);
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);

    }

}