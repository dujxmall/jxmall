<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-29
 * Time: 12:39
 */

namespace app\controllers\mall;


use app\forms\mall\poster\GoodsPosterForm;
use app\forms\mall\poster\InviterPosterForm;

class PosterController extends Controller
{


    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:45
     * Note:商品海报
     * @return \yii\web\Response
     */
    public function actionGoods()
    {
        $form = new GoodsPosterForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-29
     * @Time: 12:40
     * @Note:推广海报
     */
    public function actionInviter()
    {
        $form = new InviterPosterForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        return $this->asJson($form->search());
    }

}
