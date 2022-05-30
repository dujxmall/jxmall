<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/5/23
 * Time: 13:36
 * Note:
 */

namespace app\controllers\notify;


use app\controllers\BaseController;
use app\models\Cash;
use yii\helpers\Json;

class JoinpayController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/5/23
     * Time：13:37
     * Note：单笔代付回调
     */
    public function actionCashSinglePay()
    {
        $res = $this->request->getRawBody();
        if (!$res) {
            \Yii::warning('回调内容为空');
            return 'success';
        }
        \Yii::warning($res);
        $params = Json::decode($res);
        if ($params && is_array($params)) {
            $cash = Cash::findOne(['order_no' => $params['merchantOrderNo']]);
            if ($cash) {
                if ($params['status'] == 205) {//交易成功
                    $cash->is_price = 1;
                    $cash->joinpay_status = 1;
                    if ($cash->save()) {
                        return Json::encode(['statusCode' => 2001, 'message' => '处理成功']);
                    }
                } else {
                    $cash->joinpay_status = 0;
                    $joinpayDesc = $cash->joinpay_desc;
                    if ($joinpayDesc) {
                        $cash->joinpay_desc .= date('Y-m-d H:i:s') . PHP_EOL;
                    } else {
                        $cash->joinpay_desc = date('Y-m-d H:i:s') . PHP_EOL;
                    }
                    $cash->joinpay_desc .= $cash->order_no . PHP_EOL;
                    $cash->joinpay_desc .= $params['errorCodeDesc'] . PHP_EOL;
                    $cash->order_no = Cash::getOrderNo($cash->user_id);//重新发起提现
                    $cash->save();
                }
                return Json::encode(['statusCode' => 2001, 'message' => '处理成功']);
            }
        }
        return Json::encode(['statusCode' => 2001, 'message' => '处理成功']);
    }

}