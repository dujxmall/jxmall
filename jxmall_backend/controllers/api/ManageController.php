<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-04
 * Time: 20:24
 */

namespace app\controllers\api;


use app\controllers\api\filters\LoginFilter;
use app\forms\api\manage\ManageDataForm;

class ManageController extends Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(),
            ['login' => LoginFilter::class]
        ); // TODO: Change the autogenerated stub
    }


    public function actionInfo()
    {
        $form = new ManageDataForm();
        return $this->asJson($form->getData());
    }

}