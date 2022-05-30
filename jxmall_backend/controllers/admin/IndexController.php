<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-09
 * Time: 17:23
 */

namespace app\controllers\admin;


use app\forms\admin\AdminDeleteForm;
use app\forms\admin\AdminEditForm;
use app\forms\admin\AdminListForm;
use app\forms\admin\IndexForm;
use app\forms\admin\LogoutForm;
use app\forms\admin\MallDeleteForm;
use app\forms\admin\MallEditForm;
use app\forms\admin\PasswordChangeForm;
use app\forms\admin\SystemEditForm;
use yii\web\Cookie;


class IndexController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 21:30
     * @Note:商城首页
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $form = new IndexForm();
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-29
     * @Time: 13:36
     * @Note:退出登录
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $form = new LogoutForm();
        return $this->asJson($form->logout());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 21:30
     * @Note:管理员新增或编辑
     * @return \yii\web\Response
     */
    public function actionAdminEdit()
    {
        $form = new AdminEditForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-29
     * @Time: 13:36
     * @Note: 修改密码
     */
    public function actionPasswordChange()
    {
        $form = new PasswordChangeForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-12
     * @Time: 17:38
     * @Note:管理账户列表
     * @return \yii\web\Response
     */
    public function actionAdminList()
    {
        $form = new AdminListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 21:45
     * @Note:系统设置
     * @return \yii\web\Response
     */
    public function actionSystemEdit()
    {
        $form = new SystemEditForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }


    public function actionDelete()
    {
        $form = new AdminDeleteForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }
}