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
use app\forms\admin\MallDeleteForm;
use app\forms\admin\MallEditForm;
use app\forms\admin\MallInfoForm;
use app\forms\admin\MallListForm;

class MallController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 21:30
     * @Note:商城首页
     * @return \yii\web\Response
     */
    public function actionList()
    {
        $form = new MallListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 21:30
     * @Note:商城新增或编辑
     * @return \yii\web\Response
     */
    public function actionEdit()
    {
        $form = new MallEditForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 21:20
     * @Note：删除商城
     */
    public function actionDelete()
    {
        $form = new MallDeleteForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-29
     * @Time: 1:35
     * @Note:商城信息
     */
    public function actionEnter()
    {
        $form = new MallInfoForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    public function actionInfo()
    {

        $form = new MallInfoForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getInfo());

    }

}