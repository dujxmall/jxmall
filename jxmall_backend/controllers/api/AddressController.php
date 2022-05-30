<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-06
 * Time: 10:11
 */

namespace app\controllers\api;

use app\helpers\AddressHelper;

class AddressController extends Controller
{

    public function actionAddressData()
    {

        return $this->asJson(['code' => 0, 'msg' => '数据请求成功', 'data' => ['address_data' => AddressHelper::getAddressData()]]);

    }
}