<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-29
 * Time: 11:30
 */

namespace app\plugins\share;


use app\helpers\FileHelper;
use app\plugins\share\listeners\CheckShareLevelUpgradeListener;
use app\plugins\share\listeners\CommonOrderCreatedListener;
use app\plugins\share\listeners\CommonOrderDetailStatusListener;

class Plugin extends \app\plugins\Plugin
{

    public function getLabel()
    {
        // TODO: Implement getLabel() method.
        return '分销';
    }

    public function getName()
    {
        // TODO: Implement getName() method.
        return "share";
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
                'name' => '分销中心',
                'url' => '/plugins/share/share',
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
//            TestListener::class,
            CheckShareLevelUpgradeListener::class,
            CommonOrderCreatedListener::class,
            CommonOrderDetailStatusListener::class
        ];

    }


    public function getType()
    {
        // TODO: Implement getType() method.
        return 0;
    }

    public function getVersion()
    {
        // TODO: Implement getVersion() method.
        return file_get_contents(__DIR__ . '/version');
    }

    public function getDsc()
    {
        // TODO: Implement getDsc() method.
        return "全民推广裂变";
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
            'name' => '分销',
            'path' => '/plugins/share',
            'children' => [
                [   'name' => '分销',
                    'path' => '/plugins/share',

                ],

            ]
        ];
    }
}