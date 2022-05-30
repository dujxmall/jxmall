<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-02
 * Time: 11:00
 */

namespace app\plugins\share\controllers\mall;


use app\plugins\Controller;
use app\plugins\share\forms\mall\ShareOrderListForm;

class OrderController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-02
     * @Time: 11:01
     * @Note:分销订单列表
     */
    public function actionList()
    {
        $form=new ShareOrderListForm();
        $form->attributes=$this->request->get();
        return $this->asJson($form->getList());

    }

}