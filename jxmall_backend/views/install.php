<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-05-06
 * Time: 13:27
 */

defined('IN_IA') or exit('Access Denied');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title>聚象商城</title>
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.15.1/lib/theme-chalk/index.css">
    <!-- import JavaScript -->
    <script src="<?= $cur_module_url ?>/statics/vue.js"></script>
    <script src="<?= $cur_module_url ?>/statics/axios.js"></script>
    <script src="<?= $cur_module_url ?>/statics/element-ui.js"></script>
    <style>
        body {
            font-size: 15px;
        }

        #center {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -180px;
            margin-left: -75px;
            width: 150px;
            height: 80px;
            font-family: "Lato", sans-serif;
            font-weight: 600;
            font-size: 16px;
            color: #fff;
            text-align: center;
            -webkit-font-smoothing: antialiased;
        }

        .skype-loader {
            width: 80px;
            height: 80px;
            position: relative;
            margin: auto;
            margin-top: 60px;
            margin-bottom: 70px;
        }

        .skype-loader .dot {
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
            height: 80px;

            animation: 1.7s dotrotate cubic-bezier(0.775, 0.005, 0.310, 1.000) infinite;
        }

        .skype-loader .dot:nth-child(1) {
            animation-delay: 0.2s;
        }

        .skype-loader .dot:nth-child(2) {
            animation-delay: 0.35s;
        }

        .skype-loader .dot:nth-child(3) {
            animation-delay: 0.45s;
        }

        .skype-loader .dot:nth-child(4) {
            animation-delay: 0.55s;
        }

        .skype-loader .dot:after,
        .skype-loader .dot .first {
            content: "";
            position: absolute;
            width: 8px;
            height: 8px;
            background: #409EFF;
            border-radius: 50%;
            left: 50%;
            margin-left: -4px;
        }

        .skype-loader .dot .first {
            background: #fff;
            margin-top: -4px;
            animation: 1.7s dotscale cubic-bezier(0.775, 0.005, 0.310, 1.000) infinite;
            animation-delay: 0.2s;
        }

        @keyframes dotrotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes dotscale {

            0%,
            10% {
                width: 16px;
                height: 16px;
                margin-left: -8px;
                margin-top: -4px;
            }

            50% {
                width: 8px;
                height: 8px;
                margin-left: -4px;
                margin-top: 0;
            }

            90%,
            100% {
                width: 16px;
                height: 16px;
                margin-left: -8px;
                margin-top: -4px;
            }
        }

        .goHome:hover {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div id="app" style="width: 55%;background-color: #FFFFFF;padding: 20px;margin: 0 auto;">
    <div class="flex-x-center" style="font-size: 40px;font-weight: bold;margin-bottom:20px;padding-top: 20px;">
        XX系统安装

    </div>

    <el-tabs v-model="activeName" style="height: 650px;">
        <el-tab-pane :disabled="protocolDisable" name="protocol" label="安装协议"
                     style="height: 100%;padding-bottom: 20px;">
            <el-scrollbar style="height: 500px;">
                {{textarea2}}
            </el-scrollbar>
            <div style="text-align: center;padding: 0 20px;margin-top: 20px;z-index: 99999;">
                <el-checkbox v-model="agree" style="margin-right: 20px;">同意协议</el-checkbox>
                <el-button style="width: 160px;" type="primary" @click="next">下一步</el-button>
            </div>
        </el-tab-pane>

        <el-tab-pane :disabled="siteDisable" name="site" label="完善站点信息">

            <el-row>
                <el-col :span="12">
                    <el-form ref="siteForm" :rules="FormRules" :model="siteForm" label-width="160px">
                        <el-form-item prop="site_name" label="站点名称">
                            <el-input v-model="siteForm.site_name"></el-input>
                        </el-form-item>

                        <el-form-item prop="mobile" label="手机号">
                            <div class="flex-row">
                                <el-input v-model="siteForm.mobile"></el-input>
                                <el-button type="primary" :size="'mini'" @click="getAuthCode" v-if="!is_send_code">
                                    获取验证码
                                </el-button>
                                <el-button type="primary" :size="'mini'" v-if="is_send_code">{{time}}s后重试</el-button>
                            </div>
                        </el-form-item>
                        <el-form-item prop="sms_code" label="验证码">
                            <el-input v-model="siteForm.sms_code"></el-input>
                        </el-form-item>


                    </el-form>

                </el-col>
            </el-row>
            <div style="text-align: center;padding: 0 20px;margin-top: 20px;z-index: 99999;">
                <el-button style="width: 160px;" type="primary" @click="register">
                    下一步
                </el-button>
            </div>

        </el-tab-pane>


        <el-tab-pane :disabled="settingDisable" name="setting" label="系统配置">
            <el-row>
                <el-col :span="12">
                    <el-form ref="settingForm" :rules="FormRules" :model="form" label-width="160px">
                        <el-form-item prop="redis_host" label="Redis主机">
                            <el-input v-model="form.redis_host"></el-input>
                        </el-form-item>
                        <el-form-item prop="redis_port" label="Redis端口">
                            <el-input v-model="form.redis_port"></el-input>
                        </el-form-item>
                        <el-form-item prop="redis_password" label="Redis密码">
                            <el-input v-model="form.redis_password"></el-input>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
            <div style="text-align: center;padding: 0 20px;margin-top: 20px;z-index: 99999;">
                <el-button style="width: 160px;" type="primary" @click="saveConfiguration">
                    保存
                </el-button>
            </div>
        </el-tab-pane>
        <el-tab-pane :disabled="startDisable" name="start" label="开始安装">
            <div class="flex-x-center" style="width: 100%;padding-top:70px;">
                <div class="skype-loader">
                    <div class="dot">
                        <div class="first"></div>
                    </div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>

                <!-- 	<section class="loader" style="position: relative;">
                    <h1></h1>
                    <div class="loading_box">
                        <div class="symbol">
                            <p>{{tips}}<br>
                                <span v-if="is_show_lowly" style="font-size: 3px;">文件释放过程中请耐心等候<br>预计时长15分钟</span>
                            </p>
                            <div></div>
                        </div>
                    </div>
                </section> -->
            </div>
            <div style="width: 100%;font-size: 35px;" class="flex-x-center">
                <div>
                    <div style="width: 100%;text-align:center ;" class="flex-x-center">{{tips}}</div>
                    <div style="font-size: 3px;margin-top: 10px;text-align: center;color: #ff4455;" v-if="!down_finish">
                        正在下载第{{cur_index+1}}个文件,共{{list.length}}个文件
                        <br>
                        文件释放下载过程中请耐心等候预计时长15分钟，请不要刷新页面，否则将会造成安装失败！
                    </div>
                </div>
            </div>
        </el-tab-pane>
        <el-tab-pane :disabled="finishDisable" name="finish" label="安装完成">
            <div style="font-size: 80px;" class="flex-x-center">
                <div style="width: 150px;height: 150px;color: #FFFFFF;background-color: #409EFF;border-radius: 50%;text-align: center;margin: 0 auto;line-height: 150px"
                     class="flex-x-center flex-y-center">
                    <div><i class="el-icon-check"></i></div>
                </div>
            </div>
            <div style="font-size: 50px;text-align: center" class="flex-x-center">恭喜你！安装完成</div>
            <div class="flex-x-center" style="margin-top: 100px;"> 请将命令加入到supervisor的守护进程中,进程数量4-8个：</div>
            <div style="background-color: #F3F3F3;height: 50px;" class="flex-x-center flex-y-center">
                <div style="color: #409EFF;">
                    {{command}}
                </div>
            </div>
            <div style="width: 100%;margin-top:60px;" class="flex-x-center flex-y-center">
                <div style="height: 60px;width: 180px;border-radius: 50px;font-size: 50px;background-color:#409EFF ;color: #FFFFFF;text-align:center;line-height: 60px;margin: 0 auto"
                     class="goHome" @click="goHome">
                    <div style="display: flex;margin: 0 auto">
                        <span style="font-size: 20px;padding-left: 20px;"> 访问主页</span>
                        <span>
								<i class="el-icon-right"></i>
							</span>
                    </div>
                </div>
            </div>
        </el-tab-pane>
    </el-tabs>


</div>


</body>


<script>
    new Vue({
        el: '#app',
        data: function () {
            const validateSpace = (rule, value, callback) => {
                if (!value) {
                    return callback(new Error('请输入必填项！'))
                } else {
                    return callback()
                }
            }
            return {
                tips: '正在安装请稍等片刻',
                visible: false,
                list: [],//代码包清单
                cur_package: '',
                cur_index: 0,
                protocolDisable: false,
                settingDisable: true,
                startDisable: true,
                finishDisable: true,
                siteDisable: true,
                activeName: 'protocol',//protocol
                tabPosition: 'left',
                agree: false,
                down_finish: false,
                is_show_lowly: false,
                time: 60,
                is_send_code: false,

                FormRules: {

                    redis_host: [{
                        required: true,
                        trigger: 'blur',
                        message: '请输入redis地址',
                    }],
                    redis_port: [{
                        required: true,
                        trigger: 'blur',
                        message: '请输入redis端口',
                    }],
                    redis_password: [{
                        required: true,
                        trigger: 'blur',
                        message: '请输入redis密码',
                    }],
                    site_name: [{
                        required: true,
                        trigger: 'blur',
                        message: '请输入站点名称',
                    }],
                    mobile: [{
                        required: true,
                        trigger: 'blur',
                        message: '请输入手机号',
                    }],
                    sms_code: [{
                        required: true,
                        trigger: 'blur',
                        message: '请输入验证码',
                    }],
                },

                form: {
                    redis_host: 'localhost',
                    redis_port: '6379',
                    redis_password: '20sx1314',
                },
                siteForm: {
                    site_name: '',
                    mobile: '',
                    sms_code: ''
                },
                command: '',
                textarea2: " 件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产本“软件产品”包括计算机软件，并可能包括相关媒体、印刷材料和“联机”或电子文档（“软件产品”）。本“软件产品”还包括对xxx系统提供给您的原“软件产品”的任何更新和补充资料。任何与本“软件产品”一同提供给您的并与单独一份最终用户许可证相关的软件产品是根据那份许可协议中的条款而授予您。您一旦安装、复制、下载、访问或以其它方式使用“软件产品”，即表示您同意接受本《协议》各项条款的约束。如您不同意本《协议》中的条款，请不要安装或使用“软件产品”；但您可将其退回原购买处，并获得全额退款"
            }
        },
        created() {
            this.getPackageList();
        },
        mounted() {


        },
        methods: {
            saveConfiguration() {
                this.$refs.settingForm.validate(valid => {
                    if (valid) {
                        var params = new URLSearchParams();
                        //你要传给后台的参数值 key/value
                        params.append('operation', 'saveRedis');
                        params.append('redis_host', this.form.redis_host);
                        params.append('redis_port', this.form.redis_port);
                        params.append('redis_password', this.form.redis_password);
                        axios.post("", params).then((res) => {
                            res = res.data;
                            if (res.code == 0) {
                                //redis 保存成功   那么开始去完善站点信息

                                this.$message.success(res.msg);
                                setTimeout(() => {
                                    this.tips = '正在下载文件...'
                                    this.activeName = 'start';
                                    this.download();
                                }, 500)

                            } else {
                                this.tips = '安装失败'
                                setTimeout(function () {
                                    this.activeName = 'setting';
                                    this.startDisable = true;
                                    this.tips = '正在安装...'
                                }, 2000)
                            }
                        }).catch(function (error) {
                            console.log(error)
                        })


                    } else {
                        console.log('error submit!!')
                        return false
                    }
                })


            },
            goHome() {
                window.location.replace(this.homeUrl)
            },

            getAuthCode() {
                this.time = 60;
                if (!this.siteForm.mobile || this.siteForm.mobile == '') {
                    this.$message.warning('请输入手机号');
                    return;
                }
                var params = new URLSearchParams();
                //你要传给后台的参数值 key/value
                params.append('operation', 'getAuthCode');
                params.append('mobile', this.siteForm.mobile);
                axios.post("", params).then((res) => {
                    res = res.data;

                    if (res.code == 0) {
                        this.$message.success(res.msg);

                        let self = this;
                        self.is_send_code = true;
                        let interval = setInterval(function () {
                            self.time--;
                            if (self.time <= 0) {
                                self.is_send_code = false;
                                clearInterval(interval);
                                return;
                            }

                        }, 1000)
                    } else {
                        this.$message.error(res.msg);
                    }
                }).catch(function (error) {
                    console.log(error)
                })


            },
            register() {
                this.$refs.siteForm.validate(valid => {
                    if (valid) {
                        var params = new URLSearchParams();
                        //你要传给后台的参数值 key/value
                        params.append('operation', 'registerSite');
                        params.append('mobile', this.siteForm.mobile);
                        params.append('site_name', this.siteForm.site_name);
                        params.append('sms_code', this.siteForm.sms_code);
                        axios.post("", params).then((res) => {
                            res = res.data;
                            if (res.code == 0) {
                                this.activeName = 'setting';
                                this.siteDisable = true;
                                this.startDisable = true;
                                this.settingDisable = false;
                            } else {
                                this.$message.error(res.msg);
                            }
                        }).catch(function (error) {
                            console.log(error)
                        })


                    } else {
                        console.log('error submit!!')
                        return false
                    }
                })
            },

            next(e) {
                if (!this.agree) {
                    MessageBox.confirm('请仔细阅读并勾选同意',
                        '提示', {
                            confirmButtonText: '确定',
                            type: 'warning'
                        }).then(() => {
                        return;
                    })
                    return
                }
                if (this.activeName == 'protocol') {
                    this.siteDisable = false;
                    this.protocolDisable = true;
                    this.activeName = 'site';
                }
            },
            start() {
                if (this.list.length) {
                    this.cur_index = 0;

                    this.download();
                } else {
                    this.$message.success('未获取到安装清单，请重试')
                }
            },

            getPackageList() {
                var params = new URLSearchParams();
                //你要传给后台的参数值 key/value
                params.append('operation', 'getPackageList');
                axios.post("", params).then((res) => {

                    res = res.data;
                    if (res.code == 0) {
                        this.list = res.data.list;
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            complete() {

                var params = new URLSearchParams();
                //你要传给后台的参数值 key/value
                params.append('operation', 'complete');
                axios.post("", params).then((res) => {
                    res = res.data;
                    if (res.code == 0) {//安装完成
                        this.command = res.data.command;

                        this.homeUrl = res.data.homeUrl;
                        this.$message.success('安装完成');
                    } else {
                        this.$message.error(res.msg);
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            download() {
                var params = new URLSearchParams();
                //你要传给后台的参数值 key/value
                params.append('operation', 'download');
                params.append('down_index', this.cur_index);
                axios.post("", params).then((res) => {
                    res = res.data;
                    if (res.code == 0) {
                        if (this.cur_index < this.list.length - 1) {
                            this.cur_index += 1;
                            this.download();
                        } else {
                            this.tips = "下载完成";
                            this.is_show_lowly = true;
                            this.down_finish = true;

                            this.complete();
                            setTimeout(() => {
                                this.activeName = 'finish';
                                this.finishDisable = false;
                                this.startDisable = true;
                                this.settingDisable = true;
                            }, 2000)
                        }
                    } else {

                    }
                }).catch(function (error) {
                    console.log(error)
                })
            }
        }
    })


</script>
</html>