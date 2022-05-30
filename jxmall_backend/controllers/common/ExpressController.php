<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-03
 * Time: 12:29
 */

namespace app\controllers\common;


use app\controllers\BaseController;
use app\core\ApiCode;
use app\forms\common\express\ExpressQueryForm;
use app\helpers\ExpressHelper;

class ExpressController extends BaseController
{
    public $enableCsrfValidation = false;

    public function actionExpress()
    {
        return $this->asJson(['code' => ApiCode::CODE_SUCCESS, 'msg' => '', 'data' => ['list' => ExpressHelper::express()]]);
    }

    public function actionQuery()
    {
        $form = new ExpressQueryForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    public function actionEorderStyle()
    {
        return $this->asJson(['code' => ApiCode::CODE_SUCCESS, 'msg' => '', 'data' => ['list' => ExpressHelper::eorderStyle()]]);

    }


}