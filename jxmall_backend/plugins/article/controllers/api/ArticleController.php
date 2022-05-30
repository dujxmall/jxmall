<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-31
 * Time: 17:48
 */

namespace app\plugins\article\controllers\api;


use app\plugins\ApiController;
use app\plugins\article\forms\api\ArticleCatListForm;
use app\plugins\article\forms\api\ArticleForm;
use app\plugins\article\forms\api\ArticleListForm;

class ArticleController extends ApiController
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-31
     * @Time: 17:50
     * @Note:分类列表
     * @return \yii\web\Response
     */
    public function actionCatList()
    {
        $form = new ArticleCatListForm();
        return $this->asJson($form->getList());
    }


    public function actionList()
    {
        $form = new ArticleListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


    public function actionArticle()
    {
        $form = new ArticleForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }


}