<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-04
 * Time: 23:30
 */

namespace app\plugins\share\forms\api;


use app\core\ApiCode;
use app\helpers\ImageHelper;
use app\models\BaseModel;
use app\models\User;
use app\models\UserParent;
use app\plugins\share\models\Share;
use app\plugins\share\models\ShareLevel;
use app\plugins\share\models\ShareOrder;

class ShareInfoForm extends BaseModel
{

    public function getInfo()
    {
        $share = Share::findOne(['user_id' => \Yii::$app->user->identity->getId()]);
        if (!$share) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '你不是分销商');
        }
        $level = ShareLevel::findOne(['mall_id' => \Yii::$app->mall->id, 'level' => $share->level]);
        if ($level) {
            $level_name = $level->name;
        } else {
            $level_name = '默认等级';
        }

        /**
         * @var User $user ;
         */
        $user = \Yii::$app->user->identity;
        $parent = $user->getParent();

        $share_order_count = ShareOrder::find()->where(['is_delete' => 0, 'mall_id' => \Yii::$app->mall->id])
            ->andWhere(['or', ['parent_id_1' => $user->id], ['parent_id_2' => $user->id], ['parent_id_3' => $user->id]])->count();

        $first_child_count = UserParent::find()->where(['parent_id' => $user->id, 'is_delete' => 0, 'level' => 1])->count();
        $child_count = UserParent::find()->where(['parent_id' => $user->id, 'is_delete' => 0])->count();
        $info = [
            'total_price' => $share->total_price,
            'level_name' => $level_name,
            'avatar_url' => $user->avatar_url,
            'nickname' => $user->nickname,
            'parent_name' => $parent ? $parent->nickname : '平台',
            'share_order_count' => $share_order_count,
            'child_count' => $child_count,
            'first_child_count' => $first_child_count,
            'user_bg' => ImageHelper::getUrl('/assets/default/user/user_bg.png'),
        ];
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['info' => $info]);
    }

}