<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-18
 * Time: 15:10
 */

namespace app\forms\mall\user;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\Level;
use app\models\User;

class LevelListForm extends BaseModel
{

    public $page;
    public $limit;


    public function rules()
    {
        return [
            [['page', 'limit'], 'integer'],
            [['limit'], 'default', 'value' => 10]
        ]; // TODO: Change the autogenerated stub
    }


    public function getList()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        /*  $list = Level::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])
              ->page($pagination, $this->limit, $this->page)
              ->orderBy('level ASC')
              ->asArray()->all();*/
        $query = Level::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        $pagination = null;

        if ($this->page) {
            $query->page($pagination, $this->limit, $this->page);
        }
        $list = $query->orderBy('level ASC')
            ->asArray()->all();

        foreach ($list as &$item) {
            $item['count'] = User::find()->where(['mall_id' => \Yii::$app->mall->id, 'level' => $item['level'], 'is_delete' => 0])->count();
        }
        unset($item);

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);
    }


}