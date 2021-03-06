<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-03
 * Time: 16:24
 */

namespace app\forms\common\express;

use app\core\ApiCode;
use app\helpers\ExpressHelper;
use app\helpers\ResponseHelper;
use app\helpers\SerializeHelper;
use app\models\ExpressSetting;
use Finecho\Logistics\Logistics;
use yii\httpclient\Client;

class ExpressQueryForm extends \app\models\BaseModel
{
    public $express_no;
    public $express_name;
    public $express_code;
    public $express_key;
    public $mall_id;


    public function rules()
    {
        return [
            [['express_no'], 'required'],
            [['mall_id'], 'integer'],
            [['express_name', 'express_no', 'express_code', 'express_key'], 'string'],
        ]; // TODO: Change the autogenerated stub
    }


    public function search()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        if ($this->mall_id) {
            $setting = ExpressSetting::findOne(['mall_id' => $this->mall_id, 'is_delete' => 0]);
        } else {
            $setting = ExpressSetting::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        }

        if (!$setting) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '快递接口未配置！');
        }
        if (!$setting->kdniao_appcode || !$setting->kdniao_customer) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '快递接口配置有误！');
        }

        if (!$this->express_key && $this->express_code) {
            $this->express_key = $this->express_code;
        }

        if (!$this->express_key) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '快递接口配置有误');
        }


        $EBusinessID = $setting->kdniao_customer;
        $ApiKey = $setting->kdniao_appcode;
        $ReqURL = "https://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx";
        $requestData = "{" .
            "'CustomerName': ''," .
            "'OrderCode': ''," .
            "'ShipperCode': '{$this->express_key}'," .
            "'LogisticCode': '{$this->express_no}'," .
            "}";
        // 组装系统级参数
        $datas = array(
            'EBusinessID' => $EBusinessID,
            'RequestType' => '1002', //免费即时查询接口指令1002/在途监控即时查询接口指令8001/地图版即时查询接口指令8003
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = urlencode(base64_encode(md5($requestData . $ApiKey)));
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        try {
            $response = $client->createRequest()
                ->setHeaders(['Content-type' => 'application/x-www-form-urlencoded'])
                ->setMethod('post')
                ->setUrl($ReqURL)
                ->setData($datas)
                ->send();
        } catch (\Exception $e) {

            return ResponseHelper::send(ApiCode::CODE_FAIL, $e->getMessage());
        }
        $res = SerializeHelper::decode($response->content);


        if ($res['State'] == 0) {
            return ResponseHelper::send(ApiCode::CODE_SUCCESS, '暂无轨迹', ['list' => isset($res['Traces']) ? array_reverse($res['Traces']) : []]);
        }
        if ($res['State'] == 2) {
            return ResponseHelper::send(ApiCode::CODE_SUCCESS, '配送在途', ['list' => isset($res['Traces']) ? array_reverse($res['Traces']) : []]);
        }
        if ($res['State'] == 3) {
            return ResponseHelper::send(ApiCode::CODE_SUCCESS, '已签收', ['list' => isset($res['Traces']) ? array_reverse($res['Traces']) : []]);
        }
        if ($res['State'] == 4) {
            return ResponseHelper::send(ApiCode::CODE_SUCCESS, '问题件', ['list' => isset($res['Traces']) ? array_reverse($res['Traces']) : []]);
        }
    }

}