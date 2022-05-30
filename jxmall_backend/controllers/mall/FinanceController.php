<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-02
 * Time: 13:28
 */

namespace app\controllers\mall;



use app\forms\mall\finance\BalanceLogListForm;

use app\forms\mall\finance\CashForm;
use app\forms\mall\finance\CashListForm;
use app\forms\mall\finance\CashSettingForm;
use app\forms\mall\finance\ChargeListForm;
use app\forms\mall\finance\IntegralLogListForm;
use app\forms\mall\finance\PriceLogListForm;
use app\models\Goods;

class FinanceController extends Controller
{
    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-02
     * @Time: 13:28
     * @Note: 佣金记录
     */
    public function actionPriceLog()
    {
        $form = new PriceLogListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-06-12
     * @Time: 13:48
     * @Note:余额明细
     * @return \yii\web\Response
     */
    public function actionBalanceLog()
    {
        $form = new BalanceLogListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:49
     * Note:提现设置
     * @return \yii\web\Response
     */
    public function actionCashSetting()
    {
        $form = new CashSettingForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 10:35
     * @Note:提现审核
     */
    public function actionHandleApply()
    {
        $form = new CashForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->handleApply());
        }
    }

    public function actionHandlePay()
    {
        $form = new CashForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->handlePay());
        }
    }

    public function actionManualPay()
    {
        $form = new CashForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->handlePay());
        }
    }

    public function actionUploadReceipt()
    {
        $form = new CashForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->uploadReceipt());
        }
    }

    public function actionCashList()
    {
        $form = new CashListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


    public function actionCharge()
    {
        $form = new ChargeListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());

    }
    public function actionIntegralLog()
    {
        $form = new IntegralLogListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }
}
