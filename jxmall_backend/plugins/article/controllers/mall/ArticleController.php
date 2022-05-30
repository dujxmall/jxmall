<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-31
 * Time: 14:41
 */

namespace app\plugins\article\controllers\mall;


use app\plugins\article\forms\mall\ArticleCatForm;
use app\plugins\article\forms\mall\ArticleCatListForm;
use app\plugins\article\forms\mall\ArticleForm;
use app\plugins\article\forms\mall\ArticleListForm;
use app\plugins\Controller;

class ArticleController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-31
     * @Time: 14:42
     * @Note:文章列表
     */
    public function actionList()
    {

        $form = new ArticleListForm();

        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());

    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-31
     * @Time: 14:42
     * @Note:文章编辑
     */
    public function actionArticle()
    {


        $form = new ArticleForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());

    }

    public function actionAllCat()
    {
        $form = new ArticleCatListForm();

        return $this->asJson($form->getList());

    }


    public function actionCat()
    {
        $form = new ArticleCatForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();

        return $this->asJson($form->search());

    }


    public function actionCatList()
    {

        $form = new ArticleCatListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-31
     * @Time: 16:28
     * @Note:删除
     * @return \yii\web\Response
     */
    public function actionCatDelete()
    {
        $form = new ArticleCatForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->delete());
        }
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-31
     * @Time: 16:10
     * @Note:文章删除
     * @return \yii\web\Response
     */
    public function actionDelete()
    {


        $form = new ArticleForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->delete());
        }


    }

}