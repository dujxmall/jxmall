<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-18
 * Time: 12:47
 */

namespace app\controllers\mall;


use app\forms\mall\user\ChargeForm;
use app\forms\mall\user\DefaultLevelListForm;
use app\forms\mall\user\LevelForm;
use app\forms\mall\user\LevelListForm;
use app\forms\mall\user\ParentChangeForm;
use app\forms\mall\user\UserForm;
use app\forms\mall\user\UserInfoForm;
use app\forms\mall\user\UserLevelChangeForm;
use app\forms\mall\user\UserListForm;
use app\forms\mall\user\UserSearchListForm;
use app\forms\mall\user\UserSettingForm;

class UserController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-18
     * @Time: 12:48
     * @Note:用户管理
     */
    public function actionIndex()
    {
        $form = new UserListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-18
     * @Time: 22:30
     * @Note:用户会员等级改变
     */
    public function actionLevelChange()
    {
        $form = new UserLevelChangeForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-28
     * @Time: 16:43
     * @Note:用户信息
     * @return \yii\web\Response
     */
    public function actionInfo()
    {
        $form = new UserInfoForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-28
     * @Time: 16:44
     * @Note:修改推荐人
     */
    public function actionParentChange()
    {
        $form = new ParentChangeForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-18
     * @Time: 14:29
     * @Note:默认等级列表
     */
    public function actionDefaultLevel()
    {
        $form = new DefaultLevelListForm();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-18
     * @Time: 15:13
     * @Note:等级列表
     * @return \yii\web\Response
     */
    public function actionLevel()
    {
        $form = new LevelListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-18
     * @Time: 15:09
     * @Note:等级编辑
     * @return \yii\web\Response
     */
    public function actionLevelEdit()
    {

        $form = new LevelForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());

    }

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:45
     * Note:等级删除
     * @return \yii\web\Response
     */
    public function actionLevelDelete()
    {
        $form = new LevelForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->delete());

    }

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:45
     * Note:备注改变
     * @return \yii\web\Response
     */
    public function actionRemarksChange()
    {
        $form = new UserForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->remarksChange());

    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-18
     * @Time: 22:06
     * @Note:用户充值
     * @return \yii\web\Response
     */
    public function actionCharge()
    {
        $form = new ChargeForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-24
     * @Time: 23:45
     * @Note:保存用户关系设置
     */
    public function actionSetting()
    {
        $form = new UserSettingForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/21
     * Time：9:41
     * Note：搜索用户
     */
    public function actionUserSearchList()
    {
        $form = new UserSearchListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


}
