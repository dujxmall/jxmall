<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-07
 * Time: 15:19
 */

namespace app\forms\mall\mall;


use app\core\ApiCode;
use app\helpers\SerializeHelper;
use app\models\BaseModel;
use app\models\Eorder;

class EorderForm extends BaseModel
{

    public $id;
    public $mall_id;
    public $name;
    public $express_key;
    public $account;
    public $password;
    public $month_code;
    public $shop_name;
    public $tpl_style;
    public $pay_type;
    public $to_home;
    public $is_default;
    public $express;
    public $shop_code;
    public $sender_name; //发件人名字
    public $sender_mobile;///发件手机号
    public $sender_province; //发件省份
    public $sender_city;//发件人城市
    public $sender_county;//发件人区域
    public $sender_address; //发件人地址


    public function rules()
    {
        return [

            [['id'], 'integer'],
            [['pay_type', 'to_home', 'is_default'], 'integer'],
            [['name', 'account', 'password', 'month_code', 'shop_name', 'tpl_style', 'shop_code', 'sender_name', 'sender_mobile', 'sender_province', 'sender_city', 'sender_county', 'sender_address'], 'string', 'max' => 45],
            [['express_key'], 'string', 'max' => 10],
            [['express'], 'safe']
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $eorder = null;
        if ($this->id) {
            $eorder = Eorder::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'id' => $this->id]);
            if (!$eorder) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '该电子面单模板不存在！');
            }
        }
        if (!$eorder) {
            $eorder = new Eorder();
        }

        if ($this->is_default) {
            Eorder::updateAll(['is_default' => 0], ['mall_id' => \Yii::$app->mall->id, 'is_default' => 1]);
        }
        $eorder->attributes = $this->attributes;
        $eorder->express = SerializeHelper::encode($this->express);
        $eorder->mall_id = \Yii::$app->mall->id;
        if (!$eorder->save()) {
            return $this->responseErrorMsg($eorder);
        }

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '保存成功');
    }

    public function search()
    {
        $eorder = Eorder::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'id' => $this->id]);
        if (!$eorder) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '该电子面单模板不存在！');
        }
        $eorder->express = SerializeHelper::decode($eorder->express);

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['eorder' => $eorder]);
    }

    public function delete()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $eorder = Eorder::findOne(['is_delete' => 0, 'id' => $this->id, 'mall_id' => \Yii::$app->mall->id]);
        if (!$eorder) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '模板不存在！');
        }
        $eorder->is_delete = 1;

        if (!$eorder->save()) {
            return $this->responseErrorMsg($eorder);
        }


        return $this->apiResponse(ApiCode::CODE_SUCCESS, '删除成功！');
    }
}