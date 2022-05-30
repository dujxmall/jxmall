<?php

return [
    // 微信配置 具体可参考EasyWechat
    'wechatConfig' => [
        'app_id' => '',         // AppID
        'secret' => '',     // AppSecret
        'token' => '',          // Token
        'aes_key' => '',                    // EncodingAESKey，兼容与安全模式下请一定要填写！！！
        /**
         * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
         * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
         */
        'response_type' => 'array',
    ],

// 微信支付配置 具体可参考EasyWechat
    'wechatPaymentConfig' => [
        'app_id' => '',
        'mch_id' => '',
        'key' => '', // API 密钥
        // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
        'cert_path' => '', // XXX: 绝对路径！！！！
        'key_path' => '',     // XXX: 绝对路径！！！！
        'notify_url' => '',     // 你也可以在下单时单独设置来想覆盖它
    ],

// 微信小程序配置 具体可参考EasyWechat
    'wechatMiniProgramConfig' => [],

// 微信开放平台第三方平台配置 具体可参考EasyWechat
    'wechatOpenPlatformConfig' => [],

// 微信企业微信配置 具体可参考EasyWechat
    'wechatWorkConfig' => [],

// 微信企业微信开放平台 具体可参考EasyWechat
    'wechatOpenWorkConfig' => [],

// 微信小微商户 具体可参考EasyWechat
    'wechatMicroMerchantConfig' => [],
];
