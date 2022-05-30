<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/3/30
 * Time: 15:06
 * Note:
 */

namespace app\plugins\integral\forms\api;


use app\core\ApiCode;
use app\helpers\DateHelper;
use app\helpers\IntegralLogHelper;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use app\plugins\integral\models\IntegralSignLog;

class IntegralSignForm extends BaseModel
{

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/30
     * Time：15:09
     * Note：签到
     */
    public function sign()
    {
        $setting = OptionHelper::get('plugin_integral_setting', $this->mall_id);
        if (!$setting || !$setting['is_sign_enabled']) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '未开启积分签到！');
        }

        //是否开启连续签到

        $is_continue = $setting['is_continue'];
        $day_integral = $setting['day_integral'];
        $continue_day = $setting['continue_day'];
        $continue_integral = $setting['continue_integral'];
        $integral = $day_integral;
        if ($is_continue) {
            if ($continue_day) {
                $str = date('Y-m-d 00:00:01', strtotime("-{$continue_day} day"));
                $count = IntegralSignLog::find()->andWhere(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0])->andWhere(['>=', 'created_time', $str])->count();
                if ($count >= $continue_day) {
                    $integral = $continue_integral;
                }
            }
        } else {
            if (!$day_integral) {
                return ResponseHelper::send(ApiCode::CODE_FAIL, '未设置单日签到积分');
            }
        }
        if (!$integral) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '签到失败,未获取到赠送积分');
        }
        $log = new IntegralSignLog();
        $log->mall_id = $this->mall_id;
        $log->user_id = \Yii::$app->user->identity->getId();
        $log->integral = $integral;
        $log->date = DateHelper::format(time(), 'Y-m-d');
        if (!$log->save()) {
            return $this->responseErrorMsg($log);
        }
        IntegralLogHelper::add($log->user_id, $log->integral, $log->date . '签到，赠送积分');
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '签到成功,获得' . $log->integral . '积分');
    }

}