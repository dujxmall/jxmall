<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-22
 * Time: 10:05
 */

namespace app\controllers\common;


use app\controllers\BaseController;
use app\helpers\AddressHelper;

class AddressController extends BaseController
{

    public function actionAddressData()
    {

        return $this->asJson(['code' => 0, 'msg' => '', 'data' => ['list' => AddressHelper::getAddressData()]]);
    }

    public function actionAreaData()
    {
        return $this->asJson(['code' => 0, 'msg' => '', 'data' => ['list' => AddressHelper::getAreaData()]]);
    }

    public function actionTownList()
    {
        return $this->asJson(['code' => 0, 'msg' => '', 'data' => ['list' => AddressHelper::getTownList($this->request->get('county_code'))]]);
    }
}