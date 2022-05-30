<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-13
 * Time: 9:10
 */

namespace app\controllers\api;


use app\forms\api\mall\AlertAdvForm;
use app\forms\api\mall\CatForm;
use app\forms\api\mall\MallSettingForm;
use app\forms\api\mall\OutsideLinkForm;
use app\forms\api\mall\TabbarForm;
use app\forms\mall\mall\PagePathForm;

class MallController extends Controller
{
    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-13
     * @Time: 9:39
     * @Note:
     */
    public function actionTabbar()
    {
        $form = new TabbarForm();
        return $this->asJson($form->search());
    }


    public function actionAbout()
    {
        $form = new MallSettingForm();
        return $this->asJson($form->getAbout());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-18
     * @Time: 10:00
     * @Note:弹窗广告
     */
    public function actionAlertAdv()
    {
        $form = new AlertAdvForm();
        return $this->asJson($form->search());
    }

    /**
     * Created by: ganxh
     * Date: 2021/12/3
     * Time: 13:51
     * Note:获取商城设置
     */
    public function actionSetting()
    {

        $form = new MallSettingForm();
        return $this->asJson($form->getSetting());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/23
     * Time：14:24
     * Note：获取版本审核
     */
    public function actionChecking()
    {
        $form = new MallSettingForm();
        return $this->asJson($form->getCheckingVersions());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/5/28
     * Time：11:13
     * Note：外部链接
     */
    public function actionOutsideLink()
    {
        $form = new OutsideLinkForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }
}
