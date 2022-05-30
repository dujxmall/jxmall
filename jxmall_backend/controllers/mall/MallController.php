<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-29
 * Time: 1:35
 */

namespace app\controllers\mall;


use app\forms\mall\mall\AlertAdvForm;
use app\forms\mall\mall\CheckingForm;
use app\forms\mall\mall\DiyForm;
use app\forms\mall\mall\DiyPageListForm;
use app\forms\mall\mall\EorderForm;
use app\forms\mall\mall\EorderListForm;
use app\forms\mall\mall\FeedbackListForm;
use app\forms\mall\mall\FreightForm;
use app\forms\mall\mall\FreightListForm;
use app\forms\mall\mall\HotImageForm;
use app\forms\mall\mall\HotImageListForm;
use app\forms\mall\mall\MallDataForm;
use app\forms\mall\mall\MallSettingForm;
use app\forms\mall\mall\OutsideLinkForm;
use app\forms\mall\mall\OutsideLinkListForm;
use app\forms\mall\mall\PageEditForm;
use app\forms\mall\mall\PagePathForm;
use app\forms\mall\mall\PaymentForm;
use app\forms\mall\mall\TabbarSettingForm;
use app\forms\mall\mall\UploadSettingForm;
use app\forms\mall\mall\UserCenterSettingForm;
use app\forms\mall\mall\UserManageSettingForm;

class MallController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-11
     * @Time: 10:12
     * @Note:获取预设组件
     */
    public function actionComponents()
    {
        $form = new DiyForm();
        return $this->asJson($form->getComponents());
    }

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:47
     * Note:弹窗广告
     * @return \yii\web\Response
     */
    public function actionAlertAdv()
    {
        $form = new AlertAdvForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-21
     * @Time: 13:06
     * @Note:diy 编辑
     * @return \yii\web\Response
     */
    public function actionDiyEdit()
    {
        $form = new PageEditForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        } else {
            $form->attributes = $this->request->get();
            return $this->asJson($form->search());
        }
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-21
     * @Time: 13:06
     * @Note:diy 设置
     * @return \yii\web\Response
     */
    public function actionDiy()
    {
        $form = new DiyPageListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-08
     * @Time: 23:09
     * @Note:diy 页面删除
     */
    public function actionDiyDelete()
    {
        $form = new PageEditForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->delete());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-10
     * @Time: 8:03
     * @Note:设为主页
     */
    public function actionDiyHome()
    {
        $form = new PageEditForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->setHome());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-12
     * @Time: 22:42
     * @Note:tabbar 设置
     */
    public function actionTabbar()
    {
        $form = new TabbarSettingForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 17:24
     * @Note:用户中心设置
     * @return \yii\web\Response
     */
    public function actionUserCenter()
    {
        $form = new UserCenterSettingForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 17:24
     * @Note:推广中心设置
     * @return \yii\web\Response
     */
    public function actionUserManage()
    {
        $form = new UserManageSettingForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-15
     * @Time: 10:40
     * @Note: 商城设置
     */
    public function actionSetting()
    {
        $form = new MallSettingForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 17:24
     * @Note:运费模板
     * @return \yii\web\Response
     */
    public function actionFreight()
    {
        $form = new FreightForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());

    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 17:24
     * @Note:运费模板删除
     * @return \yii\web\Response
     */
    public function actionFreightDelete()
    {
        $form = new FreightForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->delete());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 17:24
     * @Note:运费模板列表
     * @return \yii\web\Response
     */

    public function actionFreightList()
    {
        $form = new FreightListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 17:25
     * @Note:支付设置
     * @return \yii\web\Response
     */
    public function actionPayment()
    {
        $form = new PaymentForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 17:25
     * @Note:电子面单模板列表
     * @return \yii\web\Response
     */
    public function actionEorderList()
    {
        $form = new EorderListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-10
     * @Time: 17:25
     * @Note:电子面单编辑
     * @return \yii\web\Response
     */
    public function actionEorder()
    {
        $form = new EorderForm();
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
     * Time: 16:47
     * Note:电子面单删除
     * @return \yii\web\Response
     */
    public function actionEorderDelete()
    {
        $form = new EorderForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->delete());
    }

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:47
     * Note:数据概览
     * @return \yii\web\Response
     */
    public function actionData()
    {
        $form = new MallDataForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:48
     * Note:上传设置
     * @return \yii\web\Response
     */
    public function actionUpload()
    {
        $form = new UploadSettingForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:48
     * Note:小程序页面
     * @return \yii\web\Response
     */
    public function actionPage()
    {

        $form = new PagePathForm();
        return $this->asJson($form->getList());
    }

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:48
     * Note: 页面路径编辑
     * @return \yii\web\Response
     */
    public function actionPageEdit()
    {
        $form = new PagePathForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/16
     * Time：16:34
     * Note：意见反馈
     */
    public function actionFeedback()
    {
        $form = new FeedbackListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/23
     * Time：14:22
     * Note：版本审核
     */
    public function actionChecking()
    {
        $form = new CheckingForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }


    public function actionHotImage()
    {
        $form = new HotImageForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());

    }

    public function actionHotImageList()
    {
        $form = new HotImageListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    public function actionHotImageDel()
    {
        $form = new HotImageForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->delete());
        }
    }

    public function actionOutsideLink()
    {
        $form = new OutsideLinkForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/5/28
     * Time：10:24
     * Note：外部链接
     */
    public function actionOutsideLinkList()
    {
        $form = new OutsideLinkListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

}
