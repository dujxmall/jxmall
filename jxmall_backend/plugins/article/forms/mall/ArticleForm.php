<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-31
 * Time: 15:27
 */

namespace app\plugins\article\forms\mall;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use app\plugins\article\models\Article;

class ArticleForm extends BaseModel
{

    public $id;
    public $title;
    public $detail;
    public $video;
    public $cover_pic;
    public $created_by;
    public $cat_id;

    public $sort;
    public $is_published;
    public $views;

    public function rules()
    {
        return [
            [['id', 'cat_id', 'views','sort','is_published'], 'integer'],
            [['title', 'detail', 'video', 'cover_pic', 'created_by'], 'string']
        ]; // TODO: Change the autogenerated stub
    }


    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        if (!$this->title) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '文章标题不能为空');
        }
        if (!$this->detail) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '文章详情不能为空');
        }
        if (!$this->cover_pic) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '文章封面不能为空');
        }
        if ($this->id) {
            $article = Article::getOne($this->id);
            if (!$article) {
                return ResponseHelper::send(ApiCode::CODE_FAIL, '文章不存在！');
            }
        }
        if (!$article) {
            $article = new Article();
            $article->mall_id = \Yii::$app->mall->id;
        }
        $article->attributes = $this->attributes;
        if (!$article->save()) {
            return $this->responseErrorMsg($article);
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '保存成功');
    }

    public function search()
    {

        $article = Article::getOne($this->id);
        if (!$article) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '文章不存在！');
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['article' => $article]);
    }

    public function delete()
    {
        $article = Article::getOne($this->id);
        if (!$article) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '文章不存在！');
        }
        $article->is_delete = 1;
        if (!$article->save()) {
            return $this->responseErrorMsg($article);
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '删除成功');
    }

}