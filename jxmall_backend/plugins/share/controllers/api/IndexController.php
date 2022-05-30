<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-29
 * Time: 12:37
 */


namespace app\plugins\share\controllers\api;
class IndexController extends \app\controllers\BaseController
{
    public function actionIndex()
    {
        return $this->asJson(['code' => 0, 'msg' => '']);
    }

}