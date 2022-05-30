<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 22:14
 */

namespace app\forms\mall\goods;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use app\models\Cat;

class CatForm extends BaseModel
{
    public $id;
    public $name;

    public $mall_id;
    public $cover_pic;
    public $adv_pic;
    public $is_show;

    public $type; //几级分类
    public $link;
    public $sort;
    public $parent_id;


    public function rules()
    {
        return [
            [['is_show', 'type', 'sort', 'id', 'parent_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['cover_pic', 'adv_pic'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 128],
            [['is_show', 'type', 'sort','parent_id'], 'default', 'value' => 0],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $cat = null;
        if (!$this->name) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '分类名称不能为空');
        }
        if(!$this->parent_id){
            $this->parent_id=0;
        }
        if ($this->id) {
            $cat = Cat::findOne(['is_delete' => 0, 'id' => $this->id, 'mall_id' => \Yii::$app->mall->id]);
            if (!$cat) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '该分类不存在');
            }
            $cat->link = $this->link;
            $cat->cover_pic = $this->cover_pic;
            $cat->name = $this->name;
            $cat->adv_pic = $this->adv_pic;
            $cat->sort = $this->sort;
            $cat->is_show = $this->is_show;
            $cat->parent_id = $this->parent_id;
        }
        if (!$cat) {
            $cat = new Cat();
            $cat->attributes = $this->attributes;
            $cat->mall_id = \Yii::$app->mall->id;
        }
        $cat->type = 0;
        if ($cat->parent_id) {
            $parentCat = Cat::getOne($cat->parent_id);
            if ($parentCat) {
                $cat->type += 1;
            }
        }
        if (!$cat->save()) {
            return $this->responseErrorMsg($cat);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '添加成功');

    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $cat = Cat::findOne(['id' => $this->id, 'is_delete' => 0]);
        if (!$cat) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '分类不存在');
        }
        $parentCat = null;
        if ($cat->parent_id) {
            $parentCat = Cat::getOne($cat->parent_id);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['cat' => $cat, 'parent_cat' => $parentCat]);
    }

    public function showChange()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $cat = Cat::findOne(['id' => $this->id, 'is_delete' => 0]);
        if (!$cat) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '分类不存在');
        }
        $cat->is_show = $this->is_show;
        $cat->save();
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '保存成功');
    }

    public function delete()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $is_exist = Cat::find()->where(['parent_id' => $this->id, 'is_delete' => 0])->exists();
        if ($is_exist) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '删除失败，该分类存在子分类！');
        }
        $cat = Cat::getOne($this->id);
        if (!$cat) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '分类不存在！');
        }
        $cat->is_delete = 1;
        if (!$cat->save()) {
            return $this->responseErrorMsg($cat);
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS,'删除成功！');
    }

}