<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-18
 * Time: 21:51
 */

namespace app\controllers\admin;


use app\controllers\BaseController;
use app\forms\admin\SmsForm;
use app\forms\admin\SmsSendForm;

class SmsController extends BaseController
{
    public $enableCsrfValidation=false;
    public function actionSmsCode()
    {
        $form = new SmsSendForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->sendAuthCode());
    }

    public function actionSms()
    {
        $form = new SmsForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }
}