<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 21:05
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\models\Admin;
use app\models\AdminMall;
use app\models\BaseModel;
use app\models\Mall;
use app\models\MallPlugin;
use function GuzzleHttp\Psr7\str;

class MallEditForm extends BaseModel
{

    public $id;
    public $name;
    public $is_has_expire;
    public $end_at;
    public $is_expire;
    public $is_stop;
    public $logo;
    public $detail;
    public $plugins;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_has_expire', 'is_expire', 'is_stop', 'id'], 'integer'],
            [['name', 'end_at'], 'string', 'max' => 45],
            [['logo', 'detail'], 'string', 'max' => 255],
            [['plugins'], 'safe'],
            [['is_has_expire', 'is_stop', 'is_expire'], 'default', 'value' => 0]
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        /**
         * @var Admin $admin
         */
        $admin = \Yii::$app->admin->identity;
        if (!$admin) {
            return $this->apiResponse(ApiCode::CODE_NOT_LOGIN, '请先登录');
        }
        if ($admin->admin_type == Admin::ADMIN_TYPE_OPERATOR) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '您无权限添加或修改商城信息');
        }
        if ($admin->admin_type == Admin::ADMIN_TYPE_FOUNDER) {
            $count = Mall::find()->where(['admin_id' => $admin->id, 'is_delete' => 0])->count();
            if ($count > $admin->mall_count) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '商城数量超过限制');
            }
            if ($count == $admin->mall_count) {
                if (!$this->id) {
                    return $this->apiResponse(ApiCode::CODE_FAIL, '商城数量超过限制');
                }
            }
        }
        $mall = null;
        if ($this->id) {
            $mall = Mall::findOne(['is_delete' => 0, 'id' => $this->id]);
        }
        if (!$mall) {
            $mall = new Mall();
            $mall->admin_id = \Yii::$app->admin->id;
        }
        $mall->attributes = $this->attributes;
        if ($this->is_has_expire) {
            if (!$this->end_at) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '请完善商城有效期');
            }
            $mall->end_at = strtotime($this->end_at);
        }
        if (!$mall->save()) {
            return $this->responseErrorMsg($mall);
        }
        if ($this->plugins && is_array($this->plugins) && count($this->plugins)) {
            MallPlugin::deleteAll(['mall_id' => $this->id]);
            foreach ($this->plugins as $item) {
                $mallPlugin = new MallPlugin();
                $mallPlugin->mall_id = $this->id;
                $mallPlugin->plugin = $item;
                $mallPlugin->save();
            }
        }
        if ($this->id) {
            return $this->apiResponse(ApiCode::CODE_SUCCESS, '修改成功');
        }
        $adminMall = AdminMall::findOne(['mall_id' => $mall->id]);
        if (!$adminMall) {
            $adminMall = new AdminMall();
            $adminMall->admin_id = $mall->admin_id;
            $adminMall->role = 'founder';
            $adminMall->mall_id = $mall->id;
            if (!$adminMall->save()) {
                \Yii::warning($adminMall->getFirstErrors());
            }
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '创建成功');
    }

}