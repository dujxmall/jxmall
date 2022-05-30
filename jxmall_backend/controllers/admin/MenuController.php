<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-08-07
 * Time: 19:15
 */

namespace app\controllers\admin;


use app\forms\admin\MenuEditForm;
use app\forms\admin\MenuForm;

class MenuController extends Controller
{


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-08-07
     * @Time: 22:58
     * @Note:设置操作者的菜单设置
     * @return \yii\web\Response
     */
    public function actionMenuSetting()
    {
        $form = new MenuForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getSetting());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-08-07
     * @Time: 22:58
     * @Note: 编辑操作者的菜单
     * @return \yii\web\Response
     */
    public function actionEdit()
    {
        $form = new MenuEditForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    public function actionMenus()
    {
        $form = new MenuForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getMenus());
    }

    public function actionAdminMenus()
    {
        $form = new MenuForm();
        return $this->asJson($form->getAdminMenus());
    }

}