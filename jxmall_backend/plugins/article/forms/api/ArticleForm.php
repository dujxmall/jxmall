<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-31
 * Time: 18:49
 */

namespace app\plugins\article\forms\api;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use app\plugins\article\models\Article;

class ArticleForm extends BaseModel
{

    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer']
        ]; // TODO: Change the autogenerated stub
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        $article = Article::getOne($this->id);


        if (!$article) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '文章不存在');
        }
        if (!$article->is_published) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '文章未发布');
        }
        $article->views += 1;
        $article->save();
        $info = $article->attributes;
        $info['created_at'] = $this->createdTime($info['created_at']);
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['article' => $info]);
    }

    private function createdTime($time)
    {
        $t = time() - $time;
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v . '前';
            }
        }
    }

}