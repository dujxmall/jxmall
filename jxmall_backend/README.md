<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>

Yii 2 Basic Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
rapidly creating small projects.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Build Status](https://travis-ci.com/yiisoft/yii2-app-basic.svg?branch=master)](https://travis-ci.com/yiisoft/yii2-app-basic)

目录结构
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources

环境要求
------------
     php8.0+ 
     php一定要要打开mongodb扩展
     mariadb
     mongodb
     redis
     php需要开启mongodb扩展
     最低配置 4核8G ，低于此配置将严重影响mongodb 性能
     初始账号密码 admin jxmall
 
------------

定时任务
------------
 
        0/5 * * * * ?   每5秒一次触发
        0 0/2 * * * ?   每2分钟一次触发
        0 15 10 * * ?   每天10点15分触发
        0 15 10 * * ? 2005  2005年每天10点15分触发
        0 * 14 * * ?    每天下午的 2点到2点59分每分触发
        0 0/5 14 * * ?  每天下午的 2点到2点59分(整点开始，每隔5分触发)
        0 0/5 14,18 * * ? 每天下午的 2点到2点59分(整点开始，每隔5分触发) 每天下午的 18点到18点59分(整点开始，每隔5分触发)
        0 0-5 14 * * ?  每天下午的 2点到2点05分每分触发
        0 10,44 14 ? 3 WED  3月分每周三下午的 2点10分和2点44分触发
        0 15 10 ? * MON-FRI     从周一到周五每天上午的10点15分触发
        0 15 10 15 * ?  每月15号上午10点15分触发
        0 15 10 L * ?   每月最后一天的10点15分触发
        0 15 10 ? * 6L  每月最后一周的星期五的10点15分触发
        0 15 10 ? * 6L 2002-2005    从2002年到2005年每月最后一周的星期五的10点15分触发
        0 15 10 ? * 6#3     每月的第三周的星期五开始触发
        0 0 12 1/5 * ?  每月的第一个中午开始每隔5天触发一次
        
------------



跨域：在入口文件index.php 配置
------------
    header('Content-Type: text/html;charset=utf-8');
    header('Access-Control-Allow-Origin:*'); // *允许任何网址请求
    header('Access-Control-Allow-Methods:*'); // 允许请求的类型
    header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
    header('Access-Control-Allow-Headers: *'); // 设置允许自定义请求头的字
------------


前端vue项目
------------
    菜单icon尺寸统一128x128
------------

提示 /usr/bin/env: ‘php\r’: No such file or directory
------------
     在站点目录下：vi yii -> :set fileformat=unix -> :wq
------------
mongodb
------------
   
    使用gii 生成的模型放在mongo目录下并且模型需要表明数据库和集合的名称，必须包含字段created_at,created_time,updated_at,deleted_at,is_delete
    
    并且继承BaseActiveRecord
------------



 git 打包最新版本
------------
       git archive -o lastest.zip ^HEAD
 
------------
小程序打包发行代码（必须包含version文件）
------------

 ````
      siteinfo.js 域名替换成自己的域名
      将siteinfo.js文件复制到根目录
      1.在common/vendor.js里面
      最上面
      import siteinfo from '../siteinfo.js'
      之后在最后面找到
        var n = {
              mall_id:1,
              uniacid: "-1",
              acid: "-1",
              multiid: "0",
              version: "1.0",
              siteroot: "https://jxmall.sinbel.cn/web/index.php?r=",
              design_method: "3"
            };
            e.exports = n;
            替换成 
            e.exports =sitinfo 即可
      2.project.config.json，文件的appid替换成自己的appid     
```` 
 
 
自动上传代码需要更改的地方
------------

 ````
      siteinfo.js 域名替换成自己的域名
      将siteinfo.js文件复制到根目录
      1.在common/vendor.js里面
      最上面
      import siteinfo from '../siteinfo.js'
      之后在最后面找到
        var n = {
              mall_id:1,
              uniacid: "-1",
              acid: "-1",
              multiid: "0",
              version: "1.0",
              siteroot: "",
              design_method: "3"
            };
            e.exports = n;
            替换成 
            e.exports =sitinfo 即可
      2.project.config.json，文件的appid的值替换成appid_value
      然后将此代码上传到web/standard目录下
```` 

````
h5支付授权目录  直接https://域名/
点金计划商家小票链接 https://域名/web/notify/ticket.php
````