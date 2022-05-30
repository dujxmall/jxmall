<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-29
 * Time: 0:45
 */

namespace app\controllers\mall;


use app\forms\mall\plugin\PluginListForm;

class PluginController extends Controller
{

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:46
     * Note:应用页面
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $form = new PluginListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }
}
