<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-05
 * Time: 0:05
 */

namespace app\controllers\api;


use app\forms\api\cash\CashApplyForm;
use app\forms\api\cash\CashForm;
use app\controllers\api\filters\LoginFilter;
use app\forms\api\cash\CashListForm;

class CashController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(),
            ['login' =>
                [
                    'class' => LoginFilter::class,
                ]
            ]
        ); // TODO: Change the autogenerated stub
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-05
     * @Time: 0:05
     * @Note:获取提现的
     */
    public function actionCashInfo()
    {
        $form = new CashForm();
        return $this->asJson($form->getCashInfo());
    }


    public function actionApply()
    {
        $form = new CashApplyForm();
        $form->attributes = $this->request->post();
        $this->asJson($form->apply());
    }

    public function actionList()
    {
        $form = new  CashListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


}