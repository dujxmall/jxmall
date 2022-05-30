<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-29
 * Time: 11:30
 */

namespace app\plugins\integral;


use app\helpers\FileHelper;


use app\plugins\integral\listeners\ParentChangeListener;
use yii\caching\DbDependency;

class Plugin extends \app\plugins\Plugin
{

    public function getLabel()
    {
        // TODO: Implement getLabel() method.
        return '积分营销';
    }

    public function getName()
    {
        // TODO: Implement getName() method.
        return "integral";
    }

    public function getLogo()
    {
        // TODO: Implement getLogo() method.
        $plugin = \app\models\Plugin::findOne(['name' => $this->getName(), 'is_delete' => 0]);
        $logo = null;
        if ($plugin) {
            $logo = $plugin->logo;
        }
        if ($logo) {
            return $logo;
        }
        return FileHelper::getUrlOnPlugin('/' . $this->getName() . '/logo.png');
    }

    /**
     * 获取插件菜单列表
     * @return array
     */
    public function getMenus()
    {
        // TODO: Implement getMenus() method.

        return [
            [
                'url' => '/plugins/integral/sign',
                'name' => '积分签到'
            ],

        ];
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 16:59
     * @Note:获取事件监听器
     */
    public function getListeners()
    {

        // TODO: Implement getListeners() method.
        return [
            ParentChangeListener::class
        ];
    }


    public function getType()
    {
        // TODO: Implement getType() method.
        return 1; //1 营销  2工具
    }

    public function getVersion()
    {
        // TODO: Implement getVersion() method.
        return file_get_contents(__DIR__ . '/version');
    }

    public function getDsc()
    {
        // TODO: Implement getDsc() method.
        return "积分营销促进用户活跃度";
    }

    public function getHistoryVersion()
    {
        // TODO: Implement getHistoryVersion() method.

        return [
            '1.0.0'
        ];

    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-08-08
     * @Time: 11:38
     * @Note:获取插件后台管理菜单
     * @return mixed
     */
    public function getAdminMenus()
    {
        // TODO: Implement getAdminMenus() method.

        return [
            'name' => '积分营销',
            'path' => '/plugins/integral',
            'children' => [
                [
                    'path' => '/plugins/integral/index',
                    'name' => '数据概况'
                ],
                [
                    'path' => '/plugins/integral/setting',
                    'name' => '设置'
                ],
            ]
        ];
    }
}