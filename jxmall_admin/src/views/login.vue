<template>
  <div class="login-container" style="height: 100%;">

    <div class="flex-y-center flex-x-center">
      <div>
        <div class="flex-row" style="width: 790px;display: flex;align-items: center;margin-top: 15vh;">
          <div>
            <el-image style="width:55px;height: 55px; " v-if="site_setting&&site_setting.login_logo"
                      :src="site_setting.login_logo"></el-image>
            <el-image style="width:55px;height: 55px; " v-else :src="logo"></el-image>
          </div>
          <div
            style="margin-left: 15px;display: flex;height: 50px;flex-direction: column;justify-content: space-around;">
            <div style="font-size: 25px;font-weight: 600;color: #f7faff;line-height: 1;">
              <span v-if="!site_setting&&!is_loading">聚象商城后台管理系统</span>
              <span v-if="site_setting">{{site_setting.name}}</span>
            </div>
            <div style="font-size: 8px;font-weight: 400;color: #f7faff;line-height: 1;"
                 v-if="site_setting&&site_setting.sub_name">
              {{site_setting.sub_name}}
            </div>
            <div style="font-size: 8px;font-weight: 400;color: #f7faff;line-height: 1;"
                 v-else>
              POWER BY 动力宇宙
            </div>
          </div>
        </div>
        <div class="login-window">
          <div style="padding: 0 60px">
            <div class="title">管理员登录
            </div>
            <div style="margin-top:30px">
              <el-form ref="loginForm" :model="loginForm" :rules="loginRules"
                       auto-complete="on" label-position="left">

                <el-form-item prop="username">
							<span class="svg-container">
								<svg-icon icon-class="user"/>
							</span>
                  <el-input ref="username" v-model="loginForm.username" placeholder="用户名" name="username"
                            type="text" tabindex="1" auto-complete="on"/>
                </el-form-item>

                <el-form-item prop="password">
							<span class="svg-container">
								<svg-icon icon-class="password"/>
							</span>
                  <el-input :key="passwordType" ref="password" v-model="loginForm.password"
                            :type="passwordType" placeholder="密码" name="password" tabindex="2" auto-complete="on"
                            @keyup.enter.native="handleLogin"/>
                  <span class="show-pwd" @click="showPwd">
								<svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'"/>
							</span>
                </el-form-item>

                <el-form-item prop="code">
                  <el-col :span="14">
                    <el-input ref="captcha" v-model="loginForm.captcha" placeholder="请输入验证码" name="captcha"
                              type="text" tabindex="1" autocomplete="on" @keyup.enter.native="handleLogin"/>
                  </el-col>
                  <el-col :span="10">
                    <div @click="getCaptcha" class="captcha" style="width: 100%;height: 100%;">
                      <captcha :captcha="captcha"></captcha>
                    </div>
                  </el-col>
                </el-form-item>

                <el-button :loading="loading" type="primary"
                           style="width:100%;margin-bottom:20px;margin-top: 30px;" @click.native.prevent="handleLogin">
                  登录
                </el-button>

              </el-form>
            </div>
          </div>
          <div style="width:1px;height: 346px;border-left: 1px solid #ededed">
          </div>
          <div style="padding: 0 36px">
            <span class="right-title">让生意更简单</span>
            <div style="width: 18px;height: 3px;background: #1890FF;border-radius: 2px;"></div>
            <span style="margin-top: 12px;font-size: 12px;font-family: PingFang SC;font-weight: 400;color: #999;">销售即社交，销售即运营，销售即服务</span>
            <div class="flex-x-center" style="width: 100%;">
              <el-image style="width: 270px;margin: 0 auto" :src="site_setting.login_pic"
                        v-if="site_setting&&site_setting.login_pic">
              </el-image>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="site_setting" style="position: fixed;bottom: 50px;left: 0;width: 100%;">
      <div style="width: 100%;height:30px;bottom: 0;left: 0;text-align: center;color: #ffffff;font-size: 12px">
        <span v-if="site_setting.copyright_url">
        <a target="_blank"
           href="https://beian.miit.gov.cn/">{{site_setting.copyright_url}}</a>
        </span>
        <span v-else>广州动力宇宙信息技术有限公司版权所有，侵权必究</span>
      </div>
    </div>
  </div>
</template>

<script>
  import store from '@/store'
  import {
    mapGetters
  } from 'vuex'
  import {
    getCaptchaCode
  } from '@/api/login'
  import Cookies from 'js-cookie'
  import {
    encrypt,
    decrypt
  } from '@/utils/jsencrypt'
  import Captcha from '@/components/Captcha'

  import logoImg from '@/assets/logo/login_logo.png'

  export default {
    name: 'Login',
    components: {
      Captcha
    },
    data() {
      return {
        logo: logoImg,
        captcha: '',
        codeUrl: '',
        loginForm: {
          username: 'admin',
          password: 'admin123',
          rememberMe: false,
          captcha: '',
          login_type: 0,
          uuid: ''
        },
        loginRules: {
          username: [{
            required: true,
            trigger: 'blur',
            message: '请输入您的账号'
          }],
          password: [{
            required: true,
            trigger: 'blur',
            message: '请输入您的密码'
          }],
          captcha: [{
            required: true,
            trigger: 'change',
            message: '请输入验证码'
          }]
        },
        is_loading: false,
        loading: false,
        // 验证码开关
        captchaOnOff: true,
        year: '',
        bg: '',
        site_setting: store.getters.site_setting,
        passwordType: 'password',
        // 注册开关
        register: false,
        redirect: undefined
      }
    },
    watch: {
      $route: {
        handler: function(route) {
          this.redirect = route.query && route.query.redirect
        },
        immediate: true
      }
    },
    computed: {
      site_logo() {
        return this.$store.state.site.setting.logo
      },
      loginBgStyle() {
        let style = `background: url(${this.bg}) no-repeat;background-size: cover`
        return style
      }
    },
    created() {
      var date = new Date
      this.year = date.getFullYear()
      this.getCaptcha()
      this.getCookie()
    },
    mounted() {
      this.getSiteSetting()
    },
    methods: {
      showPwd() {
        if (this.passwordType === 'password') {
          this.passwordType = ''
        } else {
          this.passwordType = 'password'
        }
        this.$nextTick(() => {
          this.$refs.password.focus()
        })
      },
      getSiteSetting() {
        this.$request({
          url: '/admin/site/login-site'
        }).then(res => {
          this.is_loading = false
          if (res.code == 0) {
            this.site_setting = res.data.setting
            store.commit('SET_SETTING', this.site_setting)
            this.$cache.local.set(
              'site-setting', JSON.stringify(this.site_setting)
            )
          }
        })
      },
      getCaptcha() {
        getCaptchaCode().then(res => {
          if (res.code == 0) {
            this.captcha = res.data.captcha
          }
        })
      },
      getCookie() {
        const username = Cookies.get('username')
        const password = Cookies.get('password')
        const rememberMe = Cookies.get('rememberMe')
        this.loginForm = {
          username: username === undefined ? this.loginForm.username : username,
          password: password === undefined ? this.loginForm.password : decrypt(password),
          rememberMe: rememberMe === undefined ? false : Boolean(rememberMe)
        }
      },
      handleLogin() {
        this.$refs.loginForm.validate(valid => {
          if (valid) {
            this.loading = true
            if (this.loginForm.rememberMe) {
              Cookies.set('username', this.loginForm.username, {
                expires: 30
              })
              Cookies.set('password', encrypt(this.loginForm.password), {
                expires: 30
              })
              Cookies.set('rememberMe', this.loginForm.rememberMe, {
                expires: 30
              })
            } else {
              Cookies.remove('username')
              Cookies.remove('password')
              Cookies.remove('rememberMe')
            }
            this.$store.dispatch('Login', this.loginForm).then(() => {
              this.$router.push({
                path: this.redirect || '/'
              }).catch(() => {
              })
            }).catch(() => {
              this.loading = false
              if (this.captchaOnOff) {
                this.getCaptcha()
              }
            })
          }
        })
      }
    }
  }
</script>

<style lang="scss">
  .captcha:hover {

    cursor: pointer;
  }

  .login-status {

    height: 70px;
  }

  .pointer :hover {
    cursor: pointer;

  }

  .login-item {
    cursor: pointer;
    font-size: 20px;
  }

  .login-item-active {
    font-size: 25px;
    color: #000000;
  }

  /* 修复input 背景不协调 和光标变色 */
  /* Detail see https://github.com/PanJiaChen/vue-element-admin/pull/927 */

  /* reset element-ui css */
  .login-container {

    /* 	background-color: #2d3a4b !important;
   */
    background-color: #626262 !important;

    .right-title {
      font-size: 16px;
      font-family: PingFang SC;
      font-weight: 400;
      color: #1890FF;
      margin-bottom: 12px;
    }
    .login-window {
      width: 790px;
      height: 440px;
      background: #fff;
      border-radius: 4px;
      margin-top: 30px;
      display: flex;
      justify-content: space-between;
      padding-top: 50px;
      box-sizing: border-box;
      box-shadow: 0px 0px 20px 0px #B0B1B5;
      .title {
        height: 20px !important;
        font-size: 16px;
        font-weight: 400;
        color: #1890FF;
        line-height: 20px;
      }
      .el-input {
        display: inline-block;

        width: 85%;

        input {
          border: 0px;
          -webkit-appearance: none;
          border-radius: 0px;
          padding: 12px 5px 12px 15px;
          height: 47px;

          &:-webkit-autofill {
            box-shadow: 0 0 0px 1000px #FFFFFF inset !important;

          }
        }
      }

      .el-form-item {
        line-height: 40px;
        border-bottom: solid #f0f0f0 1px;
        position: relative;
        border-radius: 2px;
        font-size: 14px;
      }
    }

  }
</style>

<style lang="scss" scoped>
  $bg: #EFF1F6;
  $dark_gray: #889aa4;
  $light_gray: #eee;

  .login-container {
    min-height: 100%;
    width: 100%;
    background-color: $bg;
    overflow: hidden;

    .tips {
      font-size: 14px;
      color: #fff;
      margin-bottom: 10px;

      span {
        &:first-of-type {
          margin-right: 16px;
        }
      }
    }

    .svg-container {
      padding: 6px 5px 6px 15px;
      color: $dark_gray;
      vertical-align: middle;
      width: 30px;
      display: inline-block;
    }

    .title-container {
      position: relative;

      .title {
        font-size: 26px;

        margin: 0px auto 40px auto;
        text-align: center;
        font-weight: bold;
      }
    }

    .show-pwd {
      position: absolute;
      right: 10px;
      top: 7px;
      font-size: 16px;
      color: $dark_gray;
      cursor: pointer;
      user-select: none;
    }
  }
</style>
