<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-02
 * Time: 19:39
 */

namespace app\controllers\mall;


use app\forms\mall\order\ExcelSendForm;
use app\forms\mall\order\ExpressLogEorderForm;
use app\forms\mall\order\ExpressLogListForm;
use app\forms\mall\order\OrderAddressModifyForm;
use app\forms\mall\order\OrderExportForm;
use app\forms\mall\order\OrderForm;
use app\forms\mall\order\OrderListForm;
use app\forms\mall\order\OrderRefundForm;
use app\forms\mall\order\OrderRefundListForm;
use app\forms\mall\order\OrderSendForm;

class OrderController extends Controller
{
    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-02
     * @Time: 19:40
     * @Note:订单列表
     */
    public function actionList()
    {
        $form = new OrderListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-02
     * @Time: 22:18
     * @Note:订单详情
     */
    public function actionDetail()
    {
        $form = new OrderForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-03
     * @Time: 10:18
     * @Note:修改收货信息
     */
    public function actionAddressModify()
    {
        $form = new OrderAddressModifyForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-03
     * @Time: 15:39
     * @Note:发货
     * @return \yii\web\Response
     */
    public function actionSend()
    {
        $form = new OrderSendForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-03
     * @Time: 15:36
     * @Note:确认收货
     */
    public function actionConfirm()
    {
        $form = new OrderForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->confirm());
    }

    /**
     * Created by: ganxh
     * Date: 2021/11/9
     * Time: 14:18
     * Note:订单结束
     * @return \yii\web\Response
     */
    public function actionFinish()
    {
        $form = new OrderForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->finish());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-28
     * @Time: 23:46
     * @Note:关闭订单
     * @return \yii\web\Response
     */
    public function actionClose()
    {
        $form = new OrderForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->close());
    }

    public function actionPay()
    {
        $form = new OrderForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->pay());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-13
     * @Time: 13:52
     * @Note:售后订单
     * @return \yii\web\Response
     */
    public function actionRefund()
    {
        $form = new OrderRefundListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-13
     * @Time: 13:52
     * @Note:售后订单处理状态
     * @return \yii\web\Response
     */
    public function actionRefundStatus()
    {
        $form = new OrderRefundForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-13
     * @Time: 13:52
     * @Note:售后打款
     * @return \yii\web\Response
     */
    public function actionRefundPay()
    {
        $form = new OrderRefundForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->pay());
    }


    public function actionExpressLog()
    {
        $form = new ExpressLogListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    public function actionExpressLogEorder()
    {
        $form = new ExpressLogEorderForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->setEorder());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2022-01-19
     * @Time: 19:08
     * @Note:订单批量发货
     * @return \yii\web\Response
     */
    public function actionExcelSend()
    {
        $form = new ExcelSendForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->send());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2022-01-15
     * @Time: 13:41
     * @Note:
     * @return \yii\web\Response
     */
    public function actionExport()
    {
        $form = new OrderExportForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }
}
