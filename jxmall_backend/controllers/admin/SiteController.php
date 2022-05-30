<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-29
 * Time: 8:52
 */

namespace app\controllers\admin;


use app\controllers\admin\filters\AdminFilter;
use app\controllers\BaseController;
use app\core\ApiCode;
use app\forms\admin\CheckQueueForm;
use app\forms\admin\SiteSettingForm;
use app\forms\admin\SmsForm;
use app\forms\admin\UpdateForm;

class SiteController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-29
     * @Time: 8:53
     * @Note:站点设置
     */
    public function actionSetting()
    {
        $form = new SiteSettingForm();
        if ($this->request->isPost) {
            if (\Yii::$app->admin->isGuest) {
                $headers = \Yii::$app->request->headers;
                if ($headers && $headers['x-admin-token']) {
                    if (\Yii::$app->admin->loginByAccessToken($headers['x-admin-token'])) {

                    } else {
                        \Yii::$app->response->data = [
                            'code' => ApiCode::CODE_NOT_LOGIN,
                            'msg' => '您不是超级管理员，没有访问权限!',
                        ];
                        return false;
                    }
                } else {
                    \Yii::$app->response->data = [
                        'code' => ApiCode::CODE_NOT_LOGIN,
                        'msg' => '您不是超级管理员，没有访问权限!',
                    ];
                    return false;
                }
            }
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }

    public function actionLoginSite()
    {
        $form = new SiteSettingForm();

        return $this->asJson($form->baseInfo());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-22
     * @Time: 13:47
     * @Note:检查更新
     */
    public function actionCheckUpdate()
    {
        $form = new UpdateForm();

        return $this->asJson($form->check());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-22
     * @Time: 13:36
     * @Note:更新
     */
    public function actionUpdate()
    {
        $form = new UpdateForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->update());
    }

    public function actionClearCache()
    {

        if (\Yii::$app->admin->identity->admin_type != 0) {
            return $this->asJson(['code' => ApiCode::CODE_FAIL, 'msg' => '你没有权限清理缓存']);
        }

        \Yii::$app->cache->flush();
        return $this->asJson(['code' => ApiCode::CODE_SUCCESS, 'msg' => '清理成功']);
    }

    public function actionCheckQueue()
    {
        $form = new CheckQueueForm();
        if ($this->request->isPost) {
            return $this->asJson($form->createJob());
        }
        $form->attributes=$this->request->get();
        return $this->asJson($form->checkJob());
    }


}