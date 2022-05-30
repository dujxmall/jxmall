<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 21:58
 */

namespace app\controllers\mall;


use app\core\ApiCode;
use app\forms\mall\goods\CatForm;
use app\forms\mall\goods\CatLevelForm;
use app\forms\mall\goods\CatListForm;
use app\forms\mall\goods\GoodsForm;
use app\forms\mall\goods\GoodsListForm;
use app\forms\mall\goods\GoodsUpDownJobForm;


class GoodsController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-13
     * @Time: 16:25
     * @Note:商品列表
     * @return \yii\web\Response
     */
    public function actionList()
    {
        $form = new GoodsListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 22:29
     * @Note:商品编辑
     */
    public function actionEdit()
    {
        $form = new GoodsForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 22:35
     * @Note:商品删除
     */
    public function actionDelete()
    {
        $form = new GoodsForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->delete());
        }
        return $this->asJson(['code' => ApiCode::CODE_SUCCESS, 'msg' => '未处理任何操作']);
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-27
     * @Time: 12:04
     * @Note:上下架
     * @return \yii\web\Response
     */
    public function actionStatusChange()
    {
        $form = new GoodsForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->statusChange());
        }
        return $this->asJson(['code' => ApiCode::CODE_SUCCESS, 'msg' => '未处理任何操作']);
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-11
     * @Time: 9:18
     * @Note:推荐设置
     * @return \yii\web\Response
     */
    public function actionRecommendChange()
    {
        $form = new GoodsForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->recommendChange());
        }
        return $this->asJson(['code' => ApiCode::CODE_SUCCESS, 'msg' => '未处理任何操作']);
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 22:28
     * @Note:分类编辑
     * @return \yii\web\Response
     */
    public function actionCatEdit()
    {
        $form = new CatForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-27
     * @Time: 14:49
     * @Note:分类列表
     */
    public function actionCatList()
    {
        $form = new CatListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->query());
    }

    /**
     * Created by: ganxh
     * Date: 2021/10/9
     * Time: 16:49
     * Note:所有分类
     * @return \yii\web\Response
     */
    public function actionCat()
    {
        $form = new CatListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-13
     * @Time: 16:25
     * @Note:展示分类
     * @return \yii\web\Response
     */
    public function actionCatShow()
    {
        $form = new CatForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->showChange());
    }

    /**
     * Created by: ganxh
     * Date: 2021/11/9
     * Time: 14:17
     * Note:
     * @return \yii\web\Response
     */
    public function actionCatLevel()
    {
        $form = new CatLevelForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    public function actionAllCatLevel()
    {
        $form = new CatLevelForm();
        return $this->asJson($form->getList());
    }

    /**
     * Created by: ganxh
     * Date: 2021/11/9
     * Time: 14:16
     * Note:分类删除
     * @return \yii\web\Response
     */
    public function actionCatDelete()
    {

        $form = new CatForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->delete());

    }
    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/15
     * Time：20:33
     * Note：商品上下架队列
     */
    public function actionGoodsUpDownJob()
    {
        $form = new GoodsUpDownJobForm();
        if ($this->request->isPost) {
            $form->attributes = $this->request->post();
            return $this->asJson($form->save());
        }
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());

    }
}
