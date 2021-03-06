<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-11
 * Time: 16:32
 */

namespace app\controllers\api;


use app\controllers\api\filters\LoginFilter;
use app\forms\api\cart\AddCartForm;
use app\forms\api\cart\CartDeleteForm;
use app\forms\api\cart\CartEditForm;
use app\forms\api\cart\CartForm;
use app\forms\api\cart\CartListForm;
use app\forms\mall\goods\CatListForm;

class CartController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(),
            ['login' => LoginFilter::class]
        ); // TODO: Change the autogenerated stub
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-11
     * @Time: 16:32
     * @Note:加入购物车
     */
    public function actionAddCart()
    {
        $form = new AddCartForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-11
     * @Time: 17:28
     * @Note:购物车列表
     */
    public function actionList()
    {
        $form = new CartListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-11
     * @Time: 17:33
     * @Note:获取购物车总数
     */
    public function actionCount()
    {
        $form = new CartForm();
        return $this->asJson($form->getCount());
    }

    public function actionEdit()
    {
        $form = new CartEditForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-12
     * @Time: 16:39
     * @Note:删除
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        $form = new CartDeleteForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->delete());
    }
}