<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-10-08
 * Time: 20:54
 */

namespace app\controllers\admin;


use app\core\ApiCode;
use app\helpers\ConsoleHelper;
use app\helpers\ResponseHelper;

class ConsoleController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-10-08
     * @Time: 20:55
     * @Note:数据库迁移
     */
    public function actionMigrate()
    {
        $oldApp = \Yii::$app;
        \Yii::$app = ConsoleHelper::getApp();
        $runResult = \Yii::$app->runAction('migrate/auto', ['migrationPath' => '@app/migrations', 'interactive' => false]);
        ob_start();
        $info = ob_get_clean();
        ob_implicit_flush(false);
        \Yii::$app = $oldApp;
        if ($runResult !== 0) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '迁移失败！');
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '迁移完成！', ['info' => $info]);
    }


}
