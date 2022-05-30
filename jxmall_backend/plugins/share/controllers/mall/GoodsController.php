<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-29
 * Time: 13:10
 */

namespace app\plugins\share\controllers\mall;


use app\controllers\BaseController;
use app\plugins\Controller;
use app\plugins\share\forms\mall\ShareGoodsForm;
use app\plugins\share\forms\mall\ShareGoodsPriceEditForm;

class GoodsController extends Controller
{
    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-31
     * @Time: 0:45
     * @Note:同步商品
     * @return \yii\web\Response
     */
    public function actionSync()
    {
        $form = new ShareGoodsForm();
        return $this->asJson($form->syncGoods());
    }

    public function actionList()
    {

        $form = new ShareGoodsForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-31
     * @Time: 1:18
     * @Note:设置分销商品
     */
    public function actionShareStatus()
    {

        $form = new ShareGoodsForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->shareStatus());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-31
     * @Time: 9:35
     * @Note:佣金设置
     */
    public function actionEdit()
    {

        $form = new ShareGoodsPriceEditForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }
}