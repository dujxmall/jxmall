<?php
/**
 * Created by PhpStorm.
 * Author:ganxh
 * User: cp
 * Date: 2022/1/14
 * Time: 10:16
 */

use app\helpers\WechatHelper;

/**
 * @var \app\models\WechatOrder $order ;
 */
Yii::$app->platform = \app\helpers\ConstantHelper::PLATFORM_H5;
WechatHelper::init($order ? $order->mall_id : 1);
$app = \Yii::$app->wechat->app;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="referrer" content="origin">
    <meta name="viewport"
          content="width=device-width, viewport-fit=cover, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <title>支付完成</title>
    <head>
        <script type="text/javascript" charset="UTF-8"
                src="https://wx.gtimg.com/pay_h5/goldplan/js/jgoldplan-1.0.0.js"></script>
        <script type="text/javascript">
            window.onload = function () {
                var mchData = {action: 'onIframeReady', displayStyle: 'SHOW_CUSTOM_PAGE'};
                var postData = JSON.stringify(mchData);
                parent.postMessage(postData, 'https://payapp.weixin.qq.com');
            }
        </script>
        <script src="https://res2.wx.qq.com/open/js/jweixin-1.6.0.js " type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" charset="utf-8">
            wx.config(<?php echo $app->jssdk->buildConfig(array('updateAppMessageShareData', 'updateTimelineShareData'), false, false, true, array('wx-open-launch-weapp')) ?>);
        </script>
    </head>
    <style>
        body {
            font-family: PingFang SC, "Helvetica Neue", Arial, sans-serif;
        }
    </style>
</head>
<body>
<div>
    <div style="font-size: 25px;width: 100%;text-align: center;color: #3bdd68;margin-top: 50px">
        支付完成
    </div>
    <div style="margin-top:30px">
        <div id="jump"
             style="background: #3bdd68;color: #ffffff;text-align: center;height:40px;padding: 0 20px;border-radius: 50px;width: 100px;margin: 0 auto;line-height:40px">
            返回商城
        </div>
    </div>
</div>
<script type="text/javascript">
    var jump = document.getElementById("jump");
    jump.onclick = function () {
        let mchData = {
            action: 'jumpOut',
            jumpOutUrl: '<?=\app\helpers\ServerHelper::getHost()?>/h5/'
        }
        let postData = JSON.stringify(mchData)
        parent.postMessage(postData, 'https://payapp.weixin.qq.com')
    }
</script>
</body>


</html>
