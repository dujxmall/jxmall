<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/22
 * Time: 14:05
 * Note:
 */

namespace app\helpers;


class UserHelper
{

    public static function getMyId()
    {
        if(\Yii::$app->user->isGuest){
            return 0;
        }
        return \Yii::$app->user->identity->getId();
    }
}