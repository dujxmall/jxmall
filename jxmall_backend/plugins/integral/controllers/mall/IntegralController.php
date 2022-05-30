<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-31
 * Time: 14:41
 */

namespace app\plugins\integral\controllers\mall;


use app\forms\mall\finance\IntegralLogListForm;
use app\plugins\Controller;
use app\plugins\integral\forms\mall\IntegralBirthdaySendForm;
use app\plugins\integral\forms\mall\IntegralBirthdaySendLogListForm;
use app\plugins\integral\forms\mall\SettingForm;

class IntegralController extends Controller
{

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/5/2
     * Time：23:02
     * Note：积分记录
     */
    public function actionList()
    {
        $form = new IntegralLogListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    public function actionBirthdaySend()
    {
        $form = new IntegralBirthdaySendForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->send());
    }

    public function actionBirthdaySendLog()
    {
        $form = new IntegralBirthdaySendLogListForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->getList());
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/30
     * Time：15:06
     * Note  积分配置
     */
    public function actionSetting()
    {
        $form = new SettingForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }


}