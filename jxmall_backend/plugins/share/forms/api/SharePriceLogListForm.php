<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-04
 * Time: 22:16
 */

namespace app\plugins\share\forms\api;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\CommonOrderDetail;
use app\models\Goods;
use app\models\User;
use app\plugins\share\models\SharePriceLog;
use yii\caching\DbDependency;

class SharePriceLogListForm extends BaseModel
{

    public $page;
    public $limit;
    public $status;

    public function rules()
    {
        return [
            [['page', 'status', 'limit'], 'integer'],
            [['limit'], 'default', 'value' => 10]
        ]; // TODO: Change the autogenerated stub
    }


    public function getList()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }



        $query = SharePriceLog::find()->alias('l')
            ->leftJoin(['c' => CommonOrderDetail::tableName()], 'c.id=l.common_order_detail_id')
            ->leftJoin(['u' => User::tableName()], 'u.id=c.user_id')
            ->andWhere(['l.user_id' => \Yii::$app->user->identity->id, 'l.is_delete' => 0]);
        if ($this->status != -1) {
            $query->andWhere(['l.status' => $this->status]);
        }
        $list = $query
            ->select('l.*,u.nickname,u.avatar_url,u.id as user_id,c.num,c.common_order_no')
            ->page($pagination, $this->limit, $this->page)
            ->orderBy('l.created_at DESC')
            ->asArray()
            ->all();
        foreach ($list as &$item) {
            if ($item['goods_type'] == 0) {
                $item['goods'] = Goods::getBaseInfo($item['goods_id']);
            }
            $item['created_at'] = date('Y-m-d H:i:s', $item['created_at']);
            $item['updated_at'] = date('Y-m-d H:i:s', $item['updated_at']);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);
    }

}