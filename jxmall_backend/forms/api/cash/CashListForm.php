<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-08
 * Time: 15:12
 */

namespace app\forms\api\cash;


use app\core\ApiCode;
use app\helpers\DateHelper;
use app\models\BaseModel;
use app\models\Cash;

class CashListForm extends BaseModel
{
    public $status;
    public $page;
    public $limit = 10;

    public function rules()
    {
        return [
            [['status', 'page'], 'integer'],
        ]; // TODO: Change the autogenerated stub
    }

    public function getList()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        $query = Cash::find()->where(['is_delete' => 0, 'mall_id' => \Yii::$app->mall->id, 'user_id' => \Yii::$app->user->identity->id]);

        if ($this->status == 1) { //待打款
            $query->andWhere(['is_price' => 0]);
        }
        if ($this->status == 2) {
            $query->andWhere(['is_price' => 1]);
        }
        $list = $query
            ->page($pagination, $this->limit, $this->page)
            ->orderBy('created_at DESC')
            ->asArray()
            ->all();
        foreach ($list as &$item) {
            $item['created_at'] = DateHelper::format($item['created_at']);
        }
        unset($item);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);
    }


}