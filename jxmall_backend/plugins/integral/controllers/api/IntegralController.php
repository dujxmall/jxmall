<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-31
 * Time: 17:48
 */

namespace app\plugins\integral\controllers\api;


use app\core\ApiCode;
use app\helpers\CacheHelper;
use app\helpers\DateHelper;
use app\helpers\ResponseHelper;
use app\helpers\WechatHelper;
use app\plugins\ApiController;
use app\plugins\integral\forms\api\IntegralSignForm;
use app\plugins\integral\forms\api\IntegralSignLogListForm;

class IntegralController extends ApiController
{

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/22
     * Time：14:16
     * Note：签到
     */
    public function actionSign()
    {
        $form = new IntegralSignForm();
        return $this->asJson($form->sign());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/22
     * Time：13:55
     * Note：签到记录
     */
    public function actionSignLog()
    {
        $form = new IntegralSignLogListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/22
     * Time：13:55
     * Note：签到记录
     */
    public function actionSignLogByMonth()
    {
        $form = new IntegralSignLogListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getListByMonth());
    }
}