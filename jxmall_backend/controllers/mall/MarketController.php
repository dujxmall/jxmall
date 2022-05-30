<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-11
 * Time: 22:52
 */

namespace app\controllers\mall;


use app\forms\mall\market\BirthdayUserListForm;
use app\forms\mall\market\CouponForm;
use app\forms\mall\market\CouponSendForm;

/**
 * Class MarketController
 * @package app\controllers\mall
 * @Notes 营销控制器
 */
class MarketController extends Controller
{

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:46
     * Note:优惠券
     * @return \yii\web\Response
     */
    public function actionCoupon()
    {
        $form = new CouponForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-11
     * @Time: 23:27
     * @Note:优惠券列表
     */
    public function actionCouponList()
    {
        $form = new CouponForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:46
     * Note:删除优惠券
     * @return \yii\web\Response
     */
    public function actionCouponDelete()
    {
        $form = new CouponForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->delete());
        }

    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/12
     * Time：20:55
     * @Note:搜索用户
     */
    public function actionBirthdayUser()
    {
        $form = new BirthdayUserListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }



    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2022-05-11
     * @Time: 21:54
     * @Note:赠送优惠券
     * @return \yii\web\Response
     */
    public function actionCouponSend()
    {
        $form = new CouponSendForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->send());
    }


}
