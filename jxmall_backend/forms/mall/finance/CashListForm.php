<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-02
 * Time: 13:30
 */

namespace app\forms\mall\finance;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\Cash;
use app\models\PriceLog;
use app\models\User;
use app\models\UserParent;

class CashListForm extends BaseModel
{
    public $page;
    public $limit;
    public $keyword;
    public $user_id;
    public $type;
    public $nickname;
    public $search_date;
    public $is_price;
    public $status;
    public $parent_id;
    public $cash_type;


    public function rules()
    {
        return [

            [['search_date'], 'safe'],
            [['page', 'limit', 'user_id', 'type', 'is_price', 'status', 'parent_id'], 'integer'],
            [['keyword', 'nickname', 'cash_type'], 'string'],
            [['limit'], 'default', 'value' => 10]

        ]; // TODO: Change the autogenerated stub
    }

    public function attributeLabels()
    {
        return [
            'user_id' => '用户ID'
        ]; // TODO: Change the autogenerated stub
    }


    public function getList()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        $query = Cash::find()
            ->alias('c')
            ->leftJoin(['u' => User::tableName()], 'u.id=c.user_id')
            ->andWhere(['c.is_delete' => 0, 'u.is_delete' => 0]);
        if ($this->type == 0) {
            $query->andWhere(['type' => 0]);
        }
        if ($this->type == 1) {
            $query->andWhere(['type' => 1]);
        }
        if ($this->keyword) {
            $query->andWhere(['like', 'u.nickname', $this->keyword]);
        }
        if ($this->status != -1) {
            $query->andWhere(['status' => $this->status]);
        }

        if ($this->is_price != -1) {
            $query->andWhere(['c.is_price' => $this->is_price]);
        }
        $query->andFilterWhere(['c.user_id' => $this->user_id]);
        $query->andFilterWhere(['u.nickname' => $this->nickname]);

        if ($this->cash_type != 'all') {
            $query->andFilterWhere(['c.cash_type' => $this->cash_type]);
        }
        if ($this->parent_id) {
            $uids = UserParent::find()->andWhere(['parent_id' => $this->parent_id])->select('user_id')->distinct()->column();
            $query->andWhere(['c.user_id' => $uids]);
        }
        if ($this->search_date) {
            $start_at = strtotime($this->search_date[0]);
            $end_at = strtotime($this->search_date[1]);
            $query->andWhere(['>=', 'c.created_at', $start_at]);
            $query->andWhere(['<=', 'c.created_at', $end_at]);
        }


        $list = $query->page($pagination, $this->limit, $this->page)
            ->select('u.nickname,u.avatar_url,c.*')
            ->orderBy('c.created_at desc')
            ->asArray()->all();
        foreach ($list as &$item) {
            $item['created_at'] = date('Y-m-d H:i:s', $item['created_at']);
        }
        unset($item);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);

    }

}