
### 前言


    聚象商城开源项目，一个基于Yii2.0、uniapp、vue.js、element、redis、mariadb、mongodb、php8的B2B2C电商商城系统，采用主流的互联网技术架构、全新的UI设计、支持集群部署以及拥有完整的订单流程等，代码完全开源，是一个非常适合二次开发的电商平台系统，本商城致力于为中大型企业打造一个功能完整、易于维护的B2B2C电商商城系统，采用主流web技术实现。后台管理系统包含平台管理、商品管理、订单管理、财务管理、权限管理、资源管理，插件开发等模块

### 授权

    除了开源版本，我们商业版商城，多端呈现：小程序 + H5 + APP，以及更多商用插件更多详情请查看官网
    聚象商城官网 https://www.dujxmall.com
    jxmall(聚象商城) 使用 MPL2.0 开源协议，请遵守 MPL2.0 的相关条款[条款内容](https://www.mozilla.org/en-US/MPL/2.0/)，或者联系作者获取商业授权(https://www.dujxmall.com)
    注：本产品仅供个人或企业免费研究使用，但绝不允许修改后和衍生的代码做为闭源的商业软件发布和销售，违者必追究其法律责任！
    
### 代码结构

    jxmall_admin 后台管理vue项目
    jxmall_uniapp 移动端uniapp
    jxmall_backend 服务端php项目
    
### 相关截图
![登录](https://gitee.com/dujxmall/jxmall/raw/main/files/1.png)
![2](https://gitee.com/dujxmall/jxmall/raw/main/files/2.png)

![3](https://gitee.com/dujxmall/jxmall/raw/main/files/3.png)

![4](https://gitee.com/dujxmall/jxmall/raw/main/files/4.png)

![5](https://gitee.com/dujxmall/jxmall/raw/main/files/5.png)


### 部署安装

后端环境要求
* linux
* php8
* redis
* mariadb
* mongodb
* supervisor
```
后台配置文件在config目录下面
php需要开启redis mongodb扩展
redis.php 
db.php
修改成自己相关参数
配置完参数后 在站点目录执行composer install 若依赖包安装失败，请在微信群联系我们

相关配置可以参考Yii官网文档

控制台php版本需要php8.0及以上
supervior创建守护进程
php /www/wwwroot/www.dujxmall.com/yii queue/listen
```


后台vue项目
需要安装node环境,使用淘宝镜像安装会快一点
cnpm install //安装依赖
cnpm run dev //开发
cnpm run build:prod //打包生产环境


推荐使用宝塔面板安装

### 文档教程
[安装教程](http://www.dujxmall.com/)

### 沟通与交流
本产品仍然存在许多不足之处，欢迎各位大佬提交bug以及建议
![加入群聊二维码](https://gitee.com/dujxmall/jxmall/raw/main/files/wxqrcode.png)

### 特别鸣谢

[Yii2.0](https://github.com/yiisoft/yii2)
[jianyan74](https://github.com/jianyan74/yii2-easy-wechat.git)

