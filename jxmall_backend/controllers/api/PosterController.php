<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-06
 * Time: 19:39
 */

namespace app\controllers\api;


use app\controllers\api\filters\LoginFilter;
use app\forms\api\poster\GoodsPosterForm;
use app\forms\api\poster\SharePosterForm;

class PosterController extends Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ]
        ]); // TODO: Change the autogenerated stub
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-06
     * @Time: 19:40
     * @Note:商品海报
     */
    public function actionGoodsPoster()
    {
        $form = new GoodsPosterForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->create());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-06
     * @Time: 19:49
     * @Note:分享海报
     */
    public function actionSharePoster()
    {
        $form = new SharePosterForm();

        return $this->asJson($form->create());
    }
}