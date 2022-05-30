<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/10
 * Time: 13:20
 * Note:
 */

namespace app\controllers\wechat;


use app\controllers\BaseController;
use app\models\Banner;
use app\models\LcOrder;
use app\models\WechatOrder;
use yii\web\Response;

/**
 * Class TicketController
 * @package app\controllers\wechat
 */
class TicketController extends BaseController
{
//商家小票
    public $layout = false;

    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_HTML;
        \Yii::$app->response->headers->set('X-Frame-Options', 'payapp.weixin.qq.com');
        $data = $this->request->get();
        $order_no = isset($data['out_trade_no'])?$data['out_trade_no']:'';
        $order = WechatOrder::findOne(['order_no' => $order_no, 'is_delete' => 0]);

        return $this->render('index', ['order' => $order]);
    }
}