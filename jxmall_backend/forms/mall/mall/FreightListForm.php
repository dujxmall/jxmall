<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-08
 * Time: 22:39
 */

namespace app\forms\mall\mall;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\DiyPage;
use app\models\FreightTpl;

class FreightListForm extends BaseModel
{
    public $limit;
    public $page;

    public $enabled;

    public function rules()
    {
        return [
            [['page', 'limit', 'enabled'], 'integer'],
            [['limit'], 'default', 'value' => 10]
        ]; // TODO: Change the autogenerated stub
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $query = FreightTpl::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        if ($this->enabled) {
            $query->andWhere(['enabled' => 1]);
        }
        $list = $query->page($pagination, $this->limit, $this->page)->orderBy('created_at DESC')->asArray()->all();
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);

    }
}