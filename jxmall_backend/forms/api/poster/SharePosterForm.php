<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-06
 * Time: 19:40
 */

namespace app\forms\api\poster;


use app\core\ApiCode;
use app\forms\common\grafika\GrafikaOption;
use app\helpers\OptionHelper;
use app\helpers\PosterHelper;
use app\helpers\QrcodeHelper;
use app\helpers\WechatHelper;
use app\models\Goods;
use app\models\Level;

class SharePosterForm extends GrafikaOption
{


    public function create()
    {
        WechatHelper::init(\Yii::$app->mall->id);
        $option = OptionHelper::get('inviter_poster_setting', \Yii::$app->mall->id);
        if (!$option) {
            $default_poster_setting = PosterHelper::getDefault()['inviter'];
            $option = $default_poster_setting['inviter'];
        }
        if (!\Yii::$app->user->identity->is_inviter) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '不是推广员');
        }
        $option['desc']['text'] = self::autowrap($option['desc']['font'], 0, $this->font_path, $option['desc']['text'], $option['desc']['width']);
        isset($option['name']) && $option['name']['text'] = \Yii::$app->user->identity->nickname;
        $level_name = '普通用户';
        $level = Level::getOne(\Yii::$app->user->identity->level_id);
        if ($level) {
            $level_name = $level->name;
        }
        isset($option['level']) && $option['level']['text'] = $level_name;

        $user_id = \Yii::$app->user->id;
        if (\Yii::$app->platform != "MPWX") {
            $data = \Yii::$app->request->hostInfo . "/h5/#/pages/index/index?mall_id=" . \Yii::$app->mall->id . "&pid=" . $user_id;
            $saveName = 'share/' . $user_id . '/' . '.jpg';
            $res = QrcodeHelper::create($data, $saveName);
            $qrcode_file = $res['path'];
        }
        if (\Yii::$app->platform == "MPWX") {
            $app = \Yii::$app->wechat->miniProgram;
            $response = $app->app_code->getUnlimit("pid=" . $user_id, ['page' => 'pages/index/index']);
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
                $filePath = \Yii::getAlias('@runtime/mini/qrcode/') . $user_id . '/share/';//生成的二维码保存地址
                $fileName = $response->saveAs($filePath, 'qrcode.png');
                $qrcode_file = $filePath . 'qrcode.png';

            }
        }

        isset($option['head']) && $option['head']['file_path'] = \Yii::$app->user->identity->avatar_url;
        isset($option['qr_code']) && $option['qr_code']['file_path'] = $qrcode_file;
        $editor = $this->getPoster((array)$option);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '请求成功', ['url' => $editor->qrcode_url . '?v=' . time()]);

    }

}