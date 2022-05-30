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

class GoodsPosterForm extends GrafikaOption
{
    public $goods_id;

    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
        ];
    }

    public function create()
    {
        WechatHelper::init(\Yii::$app->mall->id);
        $option = OptionHelper::get('goods_poster_setting', \Yii::$app->mall->id);
        if (!$option) {
            $default_poster_setting = PosterHelper::getDefault();
            $option = $default_poster_setting['goods'];
        }
        $goods = Goods::findOne([
            'is_delete' => 0,
            'id' => $this->goods_id,
        ]);
        if (!$goods) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '商品不存在');
        }
        $option['pic']['file_path'] = $goods->cover_pic;
        $option['desc']['text'] = self::autowrap($option['desc']['font'], 0, $this->font_path, $option['desc']['text'], $option['desc']['width']);
        isset($option['name']) && $option['name']['text'] = self::autowrap($option['name']['font'], 0, $this->font_path, $goods->name, 750 - (float)$option['name']['left'] - 40, 2);
        isset($option['nickname']) && $option['nickname']['text'] = \Yii::$app->user->identity->nickname;
        if (isset($option['price'])) {
            $price = $goods->price;
            $option['price']['text'] = '价格: ￥' . $price;
        }
        if (isset($option['price']) && isset($option['name'])) {
            //自适应
            /*  $nameSize = imagettfbbox($option['name']['font'], 0, $this->font_path, $option['name']['text']);
              $nameHeight = $option['name']['top'] + $nameSize[1] - $nameSize[7];

              $priceSize = imagettfbbox($option['price']['font'], 0, $this->font_path, $option['price']['text']);
              $priceHeight = $option['price']['top'] + $priceSize[1] - $priceSize[7];

              //compare
              if ($nameHeight > $option['price']['top'] && $priceHeight > $option['name']['top']) {
                  $option['price']['top'] = $nameHeight + 25;
              }*/
        }
        $user_id = \Yii::$app->user->id;

        if (\Yii::$app->platform != "MPWX") {
            $data = \Yii::$app->request->hostInfo . "/h5/#/pages/goods/goods?mall_id=" . \Yii::$app->mall->id . "&id=" . $this->goods_id . "&pid=" . $user_id;
            $saveName = 'goods/' . $user_id . '/' . $this->goods_id . '.jpg';

            $res = QrcodeHelper::create($data, $saveName);
            $qrcode_file = $res['path'];
        }
        if (\Yii::$app->platform == "MPWX") {
            $app = \Yii::$app->wechat->miniProgram;
            $response = $app->app_code->getUnlimit("id=" . $this->goods_id . "&pid=" . $user_id, ['page' => 'pages/goods/goods']);
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
                $filePath = \Yii::getAlias('@runtime/mini/qrcode/') . $user_id . '/goods/' . $goods->id . '/';//生成的二维码保存地址
                $fileName = $response->saveAs($filePath, 'qrcode.png');
                $qrcode_file = $filePath . 'qrcode.png';
            }
        }
        isset($option['head']) && $option['head']['file_path'] = \Yii::$app->user->identity->avatar_url;
        isset($option['qr_code']) && $option['qr_code']['file_path'] = $qrcode_file;
        $editor = $this->getPoster((array)$option);
        if (file_exists($qrcode_file)) {
            unlink($qrcode_file);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '请求成功', ['url' => $editor->qrcode_url . '?v=' . time()]);
    }

}