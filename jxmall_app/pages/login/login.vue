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
				<input placeholder="请输入手机号" v-model="form.mobile" type="text" placeholder-style="text-align:center"
					class="input-item" style="margin-top: 50rpx;">
				<input placeholder="输入密码" v-model="form.password" type="password" placeholder-style="text-align:center"
					class="input-item" style="margin-top: 50rpx;">
				<view class="flex-y-center flex-x-center" style="padding-top: 70rpx;">
					<!-- #ifdef MP -->
					<tui-button @click="getUserProfile" openType="getUserInfo" shape="circle" type="danger"
						width="550rpx" height="80rpx">立即登录
					</tui-button>
					<!-- #endif -->
					<!-- #ifdef H5 -->
					<tui-button @click="login" shape="circle" type="danger" width="550rpx" height="80rpx">立即登录
					</tui-button>
					<!-- #endif -->
				</view>
				<view class="flex-y-center flex-x-between" style="padding: 0 100rpx;">
					<view style="color: #B2B2B2;font-size: 9pt;padding-top: 40rpx;text-align: center;"
						@click="$T.go('/pages/login/sign')">
						没有账号？点此注册
					</view>
					<view style="color: #B2B2B2;font-size: 9pt;padding-top: 40rpx;text-align: center;"
						@click="$T.go('/pages/login/password-reset')">
						忘记密码
					</view>

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
				setting: null,
				form: {
					mobile: '',
					password: '',
					openId: '',
					openid: '',
				}
			};
		},
		onLoad(options) {
			let setting = uni.getStorageSync('MALL_SETTING');
			if (setting) {
				this.setting = setting;
			} else {
				this.$request({
					url: "/api/mall/setting"
				}).then(res => {
					console.log(res);
					if (res.code == 0) {
						this.setting = res.data.setting;
						uni.setStorageSync('TABBAR', res.data.tabbar);
						uni.setStorageSync('CHECKING_VERSIONS', res.data.checking_versions);
						uni.setStorageSync('MALL_SETTING', res.data.setting)
					}
				})
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
			login() {
				let wechatInfo = uni.getStorageSync('WECHAT_USER_INFO');
				if (wechatInfo) {
					this.form = Object.assign(this.form, wechatInfo);
				}
				this.form.openId = this.form.openid;
				this.$request({
					url: "/api/login/mobile",
					data: this.form,
					method: 'post',
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						let user = res.data.user;
						uni.setStorageSync('USER', user);
						this.$http.setToken(res.data
							.access_token);
						this.showModal = false;
						this.$emit('success')
						let url = uni.getStorageSync('login_before_url');
						uni.showModal({
							title: '提示',
							content: res.msg,
							showCancel: false,
							success: (e) => {
								if (url) {
									//#ifdef H5
									location.href = url;
									return;
									//#endif
									uni.reLaunch({
										url: url
									});
								} else {
									uni.reLaunch({
										url: "/pages/user/user"
									})
								}
							}
						})
					} else {
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
				if (this.form.mobile == '' || this.form.password == '') {
					uni.showModal({
						title: '提示',
						content: '账号密码不正确',
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
					this.login();
					return;
				}
				let self = this;
				uni.getUserProfile({
					desc: '使用您的头像昵称信息',
					success: (res) => {
						let {
							encryptedData,
							iv,
							rawData,
							signature
						} = res;
						let provider;
						//#ifdef MP-WEIXIN
						provider = 'weixin';
						//#endif

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
										this.login();
										return;
									}
									uni.showModal({
										title: '提示',
										content: res.msg
									})
								})
							},
							complete: (e) => {
								console.log(e);
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


		}
	};
</script>
<style scoped>
	.input-item {
		height: 80rpx;
		border: solid #f0f0f0 1rpx;
		width: 550rpx;
		border-radius: 80rpx;
		background-color: #F0F0F0;
		text-align: center;
	}
</style>
