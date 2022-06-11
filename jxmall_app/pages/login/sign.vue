<template>
	<view>
		<view style="height: 100vh;width: 100%;background-color: #FFFFFF;">
			<view style="width: 75%;margin: 0 auto;">
				<view class="flex-y-center flex-x-center" style="padding: 50rpx;padding-top:40%">
					<image v-if="!setting" src="../../static/logo.png" mode="aspectFill"
						style="width: 150rpx;height: 150rpx;border-radius: 10rpx;overflow: hidden;"></image>
					<image v-if="setting" :src="setting.login_pic" mode="aspectFill"
						style="width: 150rpx;height: 150rpx;border-radius: 10rpx;overflow: hidden;"></image>
				</view>
				<input v-model="form.pid" placeholder="推荐人ID(非必填)" maxlength="11" type="text" class="input-item"
					style="margin-top: 50rpx;">
				<input v-model="form.mobile" placeholder="请输入手机号" maxlength="11" type="text" class="input-item"
					style="margin-top: 50rpx;">
				<input placeholder="输入密码" v-model="form.password" type="password" class="input-item"
					style="margin-top: 50rpx;">
				<view class="input-item flex-y-center" style="margin-top: 50rpx;">
					<input placeholder="输入验证码" maxlength="6" v-model="form.code" type="number" style="width: 70%;">
					<view style="width: 30%;">
						<view style="width: 150rpx;height: 60rpx;font-size: 9pt;border-left: solid #B3B3B3 1rpx;"
							class="flex-y-center flex-x-center" v-if="!is_send_code" @click="getCode">{{msg}}</view>
						<view style="width: 150rpx;height: 60rpx;font-size: 9pt;border-left: solid #B3B3B3 1rpx;"
							class="flex-y-center flex-x-center" v-if="is_send_code">{{sec}}秒后重试</view>
					</view>
				</view>
				<view class="flex-y-center flex-x-center" style="padding-top: 70rpx;">
					<!-- #ifdef MP -->
					<tui-button shape="circle" type="danger" width="550rpx" height="80rpx" openType="getUserInfo"
						@click="getUserProfile">立即注册</tui-button>
					<!-- #endif -->
					<!-- #ifdef H5 -->
					<tui-button shape="circle" type="danger" width="550rpx" height="80rpx" @click="signIn">立即注册
					</tui-button>
					<!-- #endif -->
					<!-- #ifdef APP-PLUS -->
					<tui-button shape="circle" type="danger" width="550rpx" height="80rpx" @click="authApp">立即注册
					</tui-button>
					<!-- #endif -->
				</view>
				<view style="color: #B2B2B2;font-size: 9pt;padding-top: 40rpx;text-align: center;" @click="$T.back(1)">
					已有账号？点此登录
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				is_loading: false,
				msg: '获取验证码',
				sec: 180,
				is_send_code: false,
				setting: null,
				is_wechat: false,
				form: {
					pid: '',
					mobile: '',
					password: '',
					code: '',
					avatarUrl: '',
					city: "",
					country: "",
					gender: 0,
					language: "",
					nickName: "",
					openid: "",
					province: "",
					openId: '',
				}
			};
		},
		onLoad(options) {
			let setting = uni.getStorageSync('MALL_SETTING');
			if (setting) {
				this.setting = setting;
			}

			//#ifdef H5
			if (this.$wechatSdk.isWechat()) {
				let wechatInfo = uni.getStorageSync('WECHAT_USER_INFO');
				if (!wechatInfo) {
					this.wechatLogin();
				}
			}
			return;
			//#endif
		},
		methods: {
			wxLogin(code) {
				let self = this;
				this.$request({
					url: '/api/login/wechat-user-info',
					data: {
						code: code
					}
				}).then(res => {
					if (res.code === 0) {
						uni.setStorageSync('WECHAT_USER_INFO', res.data.info);
					} else {
						uni.showModal({
							title: '错误',
							content: res.msg
						})
					}
				});
			},
			wechatLogin() {
				this.is_wechat = true;
				// 通过链接 获取 code
				let code = this.$http.getUrlParam('code');
				if (!code) {
					this.$request({
						url: "/api/wechat/setting"
					}).then(res => {
						if (res.code == 0) {
							// code 不存在进入此判断
							let _baseUrl = window.location.href;
							_baseUrl = encodeURIComponent(_baseUrl);

							let appid = res.data.appid;
							let url =
								`https://open.weixin.qq.com/connect/oauth2/authorize?appid=${appid}&redirect_uri=${_baseUrl}&response_type=code&scope=snsapi_userinfo#wechat_redirect`;
							window.location.href = url;
						}
					})

				} else {
					// 获取当前 URL
					let temp = document.URL.match(/\?.*#/)[0];
					if (temp.match(/=.*&/)) {
						// 解析code
						let _code = temp.match(/=.*&/)[0];
						_code = _code.substr(1, _code.length); // 去掉 ?
						_code = _code.substr(0, _code.length - 1); // 去掉 #
						// 重置URL(去掉code参数，避免重复调用)
						window.history.replaceState({}, 0, document.URL.replace(temp.substr(0, temp.length - 1), ''));
						// 通过code请求获取openId或者用户信息
						this.wxLogin(code);
					}
				}
			},

			getCode() {
				this.is_send_code = true;
				if (this.is_loading) {
					return;
				}
				this.is_loading = true;
				this.$request({
					url: "/api/login/sms-code",
					data: this.form,
					method: 'post'
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						var time = setInterval(() => {
							this.sec -= 1;
							if (this.sec <= 0) {
								this.is_send_code = false;
								this.sec = 180;
								clearInterval(time);
							}
						}, 1000)

						uni.showModal({
							title: '提示',
							content: res.msg,
							success: (e) => {

							}
						})
					} else {
						this.is_send_code = false;
						uni.showModal({
							title: '提示',
							content: res.msg,
							showCancel: false
						})
					}
				})
			},
			signIn() {
				let wechatInfo = uni.getStorageSync('WECHAT_USER_INFO');
				if (wechatInfo) {
					this.form = Object.assign(this.form, wechatInfo);
				}
				if (this.form.openId) {
					this.form.openid = this.form.openId;
				}

				if (this.form.pid) {
					this.$http.setPid(this.form.pid)
				}
				this.$request({
					url: "/api/login/sign",
					data: this.form,
					method: 'post'
				}).then(res => {
					uni.hideLoading();
					this.is_loading = false;
					if (res.code == 0) {
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: (e) => {
								uni.redirectTo({
									url: "/pages/login/login"
								})
							}
						})
					} else {
						uni.hideLoading();
						this.is_loading = false;
						uni.showModal({
							title: '提示',
							content: res.msg,
							showCancel: false
						})
					}
				})
			},
			getUserProfile() {
				if (this.is_loading) {
					return;
				}
				if (this.form.mobile == '' || this.form.password == '' || this.form.code == '') {
					uni.showModal({
						title: '提示',
						content: '请先完善以上信息',
						showCancel: false
					})
					return;
				}
				if (!this.$util.isMobile(this.form.mobile)) {
					uni.showModal({
						title: '提示',
						content: '手机号码格式不正确！',
						showCancel: false
					})
					return;
				}
				this.is_loading = true;
				let wechatInfo = uni.getStorageSync('WECHAT_USER_INFO');
				if (wechatInfo) {
					this.signIn();
					return;
				}
				let self = this;
				uni.showLoading({
					title: '正在注册'
				})
				uni.getUserProfile({
					desc: '使用您的头像昵称信息',
					success: (res) => {
						let {
							encryptedData,
							iv,
							rawData,
							signature,
							userInfo,
						} = res;
						let provider;
						//#ifdef MP-WEIXIN
						provider = 'weixin';
						//#endif

						this.form = Object.assign(this.form, userInfo)
						uni.login({
							provider: provider,
							scopes: 'auth_user',
							success: (loginRes) => {
								this.$request({
									url: '/api/login/mpwx-user-info',
									method: 'POST',
									data: {
										code: loginRes.code,
										encryptedData: encryptedData,
										iv: iv
									}
								}).then(res => {
									this.$http.hideLoading();
									if (res.code == 0) {
										uni.setStorageSync('WECHAT_USER_INFO', res.data
											.result);
										this.form = Object.assign(this.form, res.data
											.result);
										this.signIn();
										return;
									}
									this.is_loading = false;
									uni.showModal({
										title: '提示',
										content: res.msg
									})
								})
							},
							complete: (e) => {
								this.$http.hideLoading();
							},
							fail: function(e) {
								this.$http.hideLoading();
								uni.showModal({
									title: '提示',
									content: e.errMsg
								})
							}
						});
					},
					fail: (e) => {
						console.log(e);
					}
				});
			},

			authApp() {
				if (this.is_loading) {
					return;
				}
				if (this.form.mobile == '' || this.form.password == '' || this.form.code == '') {
					uni.showModal({
						title: '提示',
						content: '请先完善以上信息',
						showCancel: false
					})
					return;
				}
				if (!this.$util.isMobile(this.form.mobile)) {
					uni.showModal({
						title: '提示',
						content: '手机号码格式不正确！',
						showCancel: false
					})
					return;
				}
				this.is_loading = true;
				let wechatInfo = uni.getStorageSync('WECHAT_USER_INFO');
				if (wechatInfo) {
					this.signIn();
					return;
				}
				let self = this;
				uni.showLoading({
					title: '正在注册'
				})
				uni.login({
					provider: 'weixin',
					success: function(loginRes) {
						// 获取用户信息
						uni.getUserInfo({
							provider: 'weixin',
							success: (infoRes) => {
								if (infoRes.errMsg == 'getUserInfo:ok') {
									let userInfo = infoRes.userInfo;
									uni.setStorageSync('WECHAT_USER_INFO', userInfo);
									self.signIn();
								}
							},
							fail: (e) => {
								uni.hideLoading();
								this.is_loading = false;
								self.$http.toast(res.msg);
							}
						});
					},
					fail: () => {
						uni.hideLoading();
						this.is_loading = false;
						self.$http.toast(res.msg);
					}
				});
			},

		}
	};
</script>
<style lang="scss" scoped>
	.bg-danger-color {
		background-color: $uni-color-danger;
	}

	.input-item {
		height: 80rpx;
		border: solid #f0f0f0 1rpx;
		width: 550rpx;
		border-radius: 80rpx;
		background-color: #F0F0F0;
		padding-left: 40rpx;
	}
</style>
