<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-29
 * Time: 0:46
 */

namespace app\controllers\admin;


use app\forms\admin\PluginForm;
use app\forms\admin\PluginListForm;
use app\forms\admin\UpdateForm;

class PluginController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-29
     * @Time: 0:46
     * @Note:插件列表
     */

    public function actionList()
    {
        $form = new PluginListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    public function actionInstall()
    {
        $form = new PluginForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->install());
    }

    public function actionUninstall()
    {
        $form = new PluginForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->uninstall());
    }


    public function actionLogo()
    {
        $form = new PluginForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
        }
        return $this->asJson($form->setLogo());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-22
     * @Time: 17:10
     * @Note:插件更新
     */
    public function actionUpdate()
    {
        $form = new PluginForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->update());
    }

}