<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-14
 * Time: 9:44
 */

namespace app\controllers\mall;


use app\core\ApiCode;
use app\forms\mall\platform\MpwxForm;
use app\forms\mall\platform\WechatForm;
use app\forms\mall\platform\WxappForm;
use yii\web\HttpException;

class PlatformController extends Controller
{

    public function actionWechat()
    {
        $form = new WechatForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }


    public function actionMpwx()
    {
        $form = new MpwxForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/30
     * Time：9:40
     * Note：上传小程序
     */
    public function actionMpwxUpload(){

        $form=new MpwxForm();
        $form->attributes=$this->request->post();
        return $this->asJson($form->upload());
    }

    public function actionWxapp()
    {
        $form = new WxappForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }

    public function actionWechatMenu()
    {
        $app = \Yii::$app->wechat->app;
        if (\Yii::$app->request->isPost) {
            $menus = \Yii::$app->request->post('form');
            $res = $app->menu->create($menus['button']);
            if ($res['errcode'] == 0) {
                return $this->asJson(['code' => ApiCode::CODE_SUCCESS, 'msg' => '发布成功']);
            }
            return $this->asJson(['code' => ApiCode::CODE_FAIL, 'msg' => "错误码:{$res['errmsg']}"]);
        }
        try {
            $list = $app->menu->list();
        } catch (HttpException $e) {
            return $this->asJson(['code' => ApiCode::CODE_FAIL, 'msg' => "获取菜单失败，errcode：{$e->formattedResponse['errcode']},errmsg：{$e->formattedResponse['errmsg']}"]);
        }
        return $this->asJson(['code' => ApiCode::CODE_SUCCESS, 'msg' => '获取成功', 'data' => $list]);
    }

}
