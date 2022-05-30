<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\User;
use app\models\UserParent;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FixController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
        return ExitCode::OK;
    }

    /**
     * Created by: ganxh
     * Date: 2022/1/20
     * Time: 11:17
     * Note:
     * @return int
     * @throws \yii\db\Exception
     */
    public function actionRelation()
    {
        \Yii::warning('维护关系链');
        \Yii::$app->db->createCommand()->truncateTable(UserParent::tableName())->execute();//清空数据表 myii_stb
        $list = User::find()->andWhere(['is_delete' => 0])->andWhere(['!=', 'parent_id', 0])->orderBy('id asc')->all();
        /**
         * @var User[] $list
         *
         */
        foreach ($list as $user) {
            $level = 1;
            $userParent = new UserParent();
            $userParent->mall_id = $user->mall_id;
            $userParent->parent_id = $user->parent_id;
            $userParent->user_id = $user->id;
            $userParent->level = $level;
            if (!$userParent->save()) {
                \Yii::warning($userParent->getFirstErrors());
            }
            $parent = User::findOne($user->parent_id);
            while (true) {
                if ($parent) {

                } else {
                    break;
                }
                if ($parent->parent_id) {
                    $level += 1;
                    $userParent = new UserParent();
                    $userParent->mall_id = $parent->mall_id;
                    $userParent->parent_id = $parent->parent_id;
                    $userParent->level = $level;
                    $userParent->user_id = $user->id;
                    $userParent->save();
                    $parent = User::findOne($parent->parent_id);
                } else {
                    break;
                }
            }
            echo '处理用户：'.$user->id.',最大深度：'.$level.PHP_EOL;
            \Yii::warning('处理用户：'.$user->id.',最大深度：'.$level);
        }
        return ExitCode::OK;
    }
}
