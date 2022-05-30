<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 19:21
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\ArrayHelper;
use app\helpers\CacheHelper;
use app\helpers\ImageHelper;
use app\helpers\MallMenuHelper;
use app\helpers\OptionHelper;
use app\models\Admin;
use app\models\BaseModel;
use app\models\Mall;
use app\models\Option;

class IndexForm extends BaseModel
{
    public function search()
    {
        /**
         * @var Admin $admin ;
         */
        $admin = \Yii::$app->admin->identity;
        $admin_info['name'] = $admin->name;
        $admin_info['is_has_expire'] = $admin->is_has_expire;
        $admin_info['username'] = $admin->username;
        $admin_info['mobile'] = $admin->mobile;
        $child_count = 0;
        $admin_info['admin_type'] = $admin->admin_type;
        if ($admin->admin_type == 0) {
            $admin_info['admin_type_name'] = '超级管理员';
            $child_count = Admin::find()->where(['created_by' => $admin->id, 'is_delete' => 0])->count();
        }
        if ($admin->admin_type == 1) {
            $admin_info['admin_type_name'] = '普通管理';
            $child_count = Admin::find()->where(['created_by' => $admin->id, 'is_delete' => 0])->count();
        }
        if ($admin->admin_type == 2) {
            $admin_info['admin_type_name'] = '操作员';
            $child_count = 0;
        }
        if ($admin->admin_type == 0) {
            $admin_info['mall_count'] = '无限制';
        } else {
            $admin_info['mall_count'] = $admin->mall_count;
        }
        $admin_info['mall_count_exist'] = Mall::find()->where(['admin_id' => $admin->id, 'is_delete' => 0])->count();
        if ($admin->is_has_expire) {
            $admin_info['expire_at'] = date('Y-m-d H:i:s', $admin->end_at);
        }
        $admin_info['child_count'] = $child_count;
        $system_info = OptionHelper::get(Option::NAME_SYS_SETTING) ?? [];
        $admin_info['avatar_url'] = ImageHelper::getUrl('/assets/default/admin/avatar.png');
        $is_admin = 0;
        $check_keys = [];
        if (\Yii::$app->admin->identity->admin_type == 0) {
            $is_admin = 1;

        } else {
            $check_keys = OptionHelper::get('mall_menus_check_keys_' . \Yii::$app->admin->identity->id);
            $check_keys = \yii\helpers\ArrayHelper::merge($check_keys, $this->getDefaultCheckKeys());
            //这里要加入默认的check_keys
        }

        //获取当前管理菜单
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', [
            'admin_info' => $admin_info,
            'is_admin' => $is_admin,
            'system_info' => $system_info,
            'check_keys' => array_values(array_unique($check_keys)),
            'menu_list' => $this->getMenuList(),
            'menus'=>MallMenuHelper::defaultMenuList(\Yii::$app->mall->id)
        ]);
    }


    private function getDefaultCheckKeys()
    {
        return [
            '/login',
            '/404',
            '*',
            '/admin/info',
            '/admin',
            '/',
            '/admin/list',
            '/admin/mall',
            '/admin/site',
            '/admin/sms',
            '/admin/plugin',
            '/admin/update',
        ];

    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-12-18
     * @Time: 23:47
     * @Note:
     */
    private function getMenuList()
    {


        if (\Yii::$app->admin->identity->admin_type == 0) {
            $list = [
                [
                    'name' => '账户信息',
                    'key' => 'adminInfo'
                ],
                [
                    'name' => '商城列表',
                    'key' => 'adminMall'
                ],
                [
                    'name' => '站点设置',
                    'key' => 'siteSetting'
                ],
                [
                    'name' => '账户列表',
                    'key' => 'adminList'
                ],
                [
                    'name' => '插件列表',
                    'key' => 'adminPlugin'
                ],
            ];


            return $list;
        }

        if (\Yii::$app->admin->identity->admin_type == 1) {
            $list = [
                [
                    'name' => '账户信息',
                    'key' => 'adminInfo'
                ],
                [
                    'name' => '账户列表',
                    'key' => 'adminList'
                ],
                [
                    'name' => '商城列表',
                    'key' => 'adminMall'
                ],
            ];

            return $list;
        }
        if (\Yii::$app->admin->identity->admin_type == 2) {
            $list = [
                [
                    'name' => '账户信息',
                    'key' => 'adminInfo'
                ],
                [
                    'name' => '商城列表',
                    'key' => 'adminMall'
                ],
            ];

            return $list;
        }
    }

}