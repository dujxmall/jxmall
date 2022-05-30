<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-30
 * Time: 9:35
 */

namespace app\plugins\share\controllers\mall;


use app\plugins\Controller;
use app\plugins\share\forms\mall\LevelForm;
use app\plugins\share\forms\mall\LevelListForm;
use app\plugins\share\forms\mall\SettingForm;
use app\plugins\share\forms\mall\ShareApplyListForm;
use app\plugins\share\forms\mall\ShareApplyStatusForm;
use app\plugins\share\forms\mall\ShareDataForm;
use app\plugins\share\forms\mall\ShareForm;
use app\plugins\share\forms\mall\ShareInfoForm;
use app\plugins\share\forms\mall\ShareListForm;


class ShareController extends Controller
{

    public function actionData()
    {

        $form = new ShareDataForm();
        return $this->asJson($form->search());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 21:34
     * @Note:新增分销商
     */
    public function actionAdd()
    {
        $form = new ShareForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->add());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-02
     * @Time: 15:50
     * @Note:申请列表
     */
    public function actionApply()
    {
        $form = new ShareApplyListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    public function actionApplyStatus()
    {
        $form = new ShareApplyStatusForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-30
     * @Time: 23:12
     * @Note:分销商等级
     * @return \yii\web\Response
     */
    public function actionInfo()
    {
        $form = new ShareInfoForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-02
     * @Time: 15:49
     * @Note:
     * @return \yii\web\Response
     */
    public function actionAutoUpgrade()
    {
        $form = new ShareForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->autoUpgrade());

    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-30
     * @Time: 23:27
     * @Note:分销商等级改变
     */
    public function actionLevelChange()
    {
        $form = new ShareForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->levelChange());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-02
     * @Time: 15:35
     * @Note:分销列表
     * @return \yii\web\Response
     */
    public function actionList()
    {
        $form = new ShareListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-02
     * @Time: 15:35
     * @Note:分销设置
     * @return \yii\web\Response
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

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-30
     * @Time: 10:10
     * @Note:等级列表
     */
    public function actionLevelList()
    {
        $form = new LevelListForm();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-30
     * @Time: 10:30
     * @Note: 保存等级
     */
    public function actionLevel()
    {
        $form = new LevelForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    public function actionLevelDelete()
    {
        $form = new LevelForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->delete());


    }

    public function actionShareDefault()
    {
        $form = new LevelForm();

        return $this->asJson($form->getDefault());
    }
}