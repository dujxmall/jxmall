<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 16:52
 */

namespace app\controllers\api;


use app\forms\api\index\DiyPageForm;
use app\forms\api\index\IndexForm;

class IndexController extends Controller
{

    public function actionIndex()
    {
        
        $form = new IndexForm();
        return $this->asJson($form->getIndex());
    }

    public function actionDiyPage()
    {
        $form = new DiyPageForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getPageData());
    }

}