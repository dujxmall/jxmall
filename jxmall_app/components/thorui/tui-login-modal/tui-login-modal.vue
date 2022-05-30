<template>
	<view class="tui-modal__container" :class="[showModal ? 'tui-modal-show' : '']" :style="{zIndex:zIndex}"
		@touchmove.stop.prevent>
		<view class="tui-modal-box"
			:style="{ width: width, padding: padding, borderRadius: radius, backgroundColor: backgroundColor,zIndex:zIndex+1 }"
			:class="[fadeIn || showModal ? 'tui-modal-normal' : 'tui-modal-scale', showModal ? 'tui-modal-show' : '']">
			<view v-if="!custom">
				<view class="tui-modal-title">授权提醒</view>
				<view class="tui-modal-content tui-mtop">请授权头像等信息，以便为您提供更好的服务</view>
				<view class="flex-x-between">
					<button class="tui-modal-btn tui-red-outline tui-circle-btn" @click="cancel">
						取消
					</button>
					<block v-if="setting&&setting.is_login_mobile==1">
						<button class="tui-modal-btn tui-red tui-circle-btn" @click="$T.go('/pages/login/login')">
							立即登录
						</button>
					</block>
					<block v-else>
						<!-- #ifdef MP -->
						<button class="tui-modal-btn tui-red tui-circle-btn" open-type="getUserInfo"
							@click="getUserProfile">
							授权登录
						</button>
						<!-- #endif -->
						<!-- #ifdef H5 -->
						<button class="tui-modal-btn tui-red tui-circle-btn" @click="login">
							授权登录
						</button>
						<!-- #endif -->
						<!-- #ifdef APP-PLUS -->
						<button class="tui-modal-btn tui-red tui-circle-btn" @click="loginAuth">
							授权登录
						</button>
						<!-- #endif -->
					</block>
				</view>
			</view>
			<view v-else>
				<slot></slot>
			</view>
		</view>
		<view class="tui-modal-mask" :class="[showModal ? 'tui-mask-show' : '']" :style="{zIndex:maskZIndex}"
			@tap="handleClickCancel"></view>
	</view>
</template>

<script>
	export default {
		name: 'tuiModal',
		props: {
			//是否显示

			width: {
				type: String,
				default: '84%'
			},
			backgroundColor: {
				type: String,
				default: '#fff'
			},
			padding: {
				type: String,
				default: '40rpx 64rpx'
			},
			radius: {
				type: String,
				default: '24rpx'
			},
			//标题
			title: {
				type: String,
				default: ''
			},
			//内容
			content: {
				type: String,
				default: ''
			},
			//内容字体颜色
			color: {
				type: String,
				default: '#999'
			},
			//内容字体大小 rpx
			size: {
				type: Number,
				default: 28
			},
			//形状 circle, square
			shape: {
				type: String,
				default: 'square'
			},
			button: {
				type: Array,
				default: function() {
					return [{
							text: '取消',
							type: 'red',
							plain: true //是否空心
						},
						{
							text: '确定',
							type: 'red',
							plain: false
						}
					];
				}
			},
			//点击遮罩 是否可关闭
			maskClosable: {
				type: Boolean,
				default: true
			},
			//淡入效果，自定义弹框插入input输入框时传true
			fadeIn: {
				type: Boolean,
				default: false
			},
			//自定义弹窗内容
			custom: {
				type: Boolean,
				default: false
			},
			//容器z-index
			zIndex: {
				type: Number,
				default: 9997
			},
			//mask z-index
			maskZIndex: {
				type: Number,
				default: 9990
			},
			show: {
				type: Boolean,
				default: false
			}
		},
		watch: {
			show(newVal, oldVal) {
				//#ifdef H5
				uni.setStorageSync('login_before_url', location.href);
				//#endif
				let pages = getCurrentPages()
				let currentPage = pages[pages.length - 1]
				let url = currentPage.route
				let options = currentPage.options
				let urlWithArgs = `/${url}?`
				for (let key in options) {
					let value = options[key]
					urlWithArgs += `${key}=${value}&`
				}
				//#ifndef H5
				uni.setStorageSync('login_before_url', urlWithArgs);
				//#endif
			}
		},

		data() {
			return {
				setting: null,
				is_wechat: false,
				showModal: false,
				is_click_login: false,
			};
		},
		created() {

		},
		mounted() {
			this.setting = uni.getStorageSync('MALL_SETTING');

			if (!this.setting) {
				this.$request({
					url: "/api/mall/setting"
				}).then(res => {

					if (res.code == 0) {
						this.setting = res.data.setting;
						uni.setStorageSync('TABBAR', res.data.tabbar);
						uni.setStorageSync('CHECKING_VERSIONS', res.data.checking_versions);
						uni.setStorageSync('MALL_SETTING', res.data.setting);
						if (this.setting.is_login_mobile == 0) {
							//#ifdef H5
							if (this.$wechatSdk.isWechat()) {
								this.wechatLogin();
							}
							return;
							//#endif
						}
					}
				})
			} else {
				if (this.setting.is_login_mobile == 0) {
					//#ifdef H5
					if (this.$wechatSdk.isWechat()) {
						this.wechatLogin();
					}
					return;
					//#endif
				}
			}
		},
		methods: {
			login() {
				this.is_click_login = true;
				this.wechatLogin();
			},
			loginAuth() {
				let self = this;
				uni.showLoading({
					title: '正在登录...'
				})
				//app 授权登录
				uni.login({
					provider: 'weixin',
					success: function(loginRes) {

						// 获取用户信息
						uni.getUserInfo({
							provider: 'weixin',
							success: function(infoRes) {
								self.$request({
										url: '/api/login/app',
										data: infoRes.userInfo,
										method: 'post'
									})
									.then(res => {
										self.$http.toast(res.msg);
										if (res.code === 0) {
											let access_token = res.data.access_token;
											access_token = access_token.trim();
											// 否则保存 access_token 到缓存 跳转到上次的页面 或者个人页 跳转后删除 LOGIN_PRE_URL 的缓存
											self.$http.setToken(access_token);
											let user = res.data.user;
											uni.setStorageSync('USER', user);
											self.showModal = false;
											let login_before_url = uni.getStorageSync(
												'login_before_url');
											if (login_before_url) {
												uni.redirectTo({
													url: full_url
												})
												return;
											}

											let pages = getCurrentPages()
											let currentPage = pages[pages.length - 1]
											let url = currentPage.route
											let options = currentPage.options
											let urlWithArgs = `/${url}?`
											for (let key in options) {
												let value = options[key]
												urlWithArgs += `${key}=${value}&`
											}
											let full_url = urlWithArgs.substring(0, urlWithArgs
												.length - 1)
											uni.redirectTo({
												url: full_url
											})

										}
									});
							},
							fail: (e) => {
								self.$http.toast(res.msg);
							}
						});
					},
					fail: () => {
						self.$http.toast(res.msg);
					}
				});
			},
			showLoginModal() {
				this.showModal = true;
			},
			cancel() {
				this.showModal = false;
				this.$emit('cancel', true)
			},
			wxLogin(code) {
				let self = this;
				this.$request({
						url: '/api/login/wechat',
						data: {
							code: code
						}
					})
					.then(res => {
						this.$http.toast(res.msg);
						if (res.code === 0) {
							uni.setStorageSync('OPENID', res.data.openid);
							let access_token = res.data.access_token;
							access_token = access_token.trim();
							// 否则保存 access_token 到缓存 跳转到上次的页面 或者个人页 跳转后删除 LOGIN_PRE_URL 的缓存
							this.$http.setToken(access_token);
							let user = res.data.user;
							uni.setStorageSync('USER', user);
							this.showModal = false;
							let login_before_url = uni.getStorageSync('login_before_url');
							if (login_before_url) {
								location.href = login_before_url;
								return;
							}
							let pages = getCurrentPages()
							let currentPage = pages[pages.length - 1]
							let url = currentPage.route
							let options = currentPage.options
							let urlWithArgs = `/${url}?`
							for (let key in options) {
								let value = options[key]
								urlWithArgs += `${key}=${value}&`
							}
							let full_url = urlWithArgs.substring(0, urlWithArgs.length - 1)
							uni.redirectTo({
								url: full_url
							})

						}
					});
			},
			wechatLogin() {

				this.is_wechat = true;
				// 通过链接 获取 code
				let code = this.$http.getUrlParam('code');

				if (!code) {
					if (this.is_click_login) {
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
					}
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
			getUserProfile() {
				let self = this;
				uni.showLoading({
					title: '正在登录...'
				})
				uni.getUserProfile({
					desc: '使用户得到更好的体验',
					success: (res) => {
						let {
							encryptedData,
							iv,
							rawData,
							signature,
							userInfo
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
									url: '/api/login/mpwx',
									method: 'POST',
									data: {
										code: loginRes.code,
										encryptedData: encryptedData,
										iv: iv,
										avatarUrl: userInfo.avatarUrl,
										nickName: userInfo.nickName
									}
								}).then(res => {
									this.$http.hideLoading();
									if (res.code == 0) {

										let user = res.data.user;
										uni.setStorageSync('USER', user);
										this.$http.setToken(res.data
											.access_token);
										this.showModal = false;
										this.$emit('success')
										/* let pages = getCurrentPages()
										let currentPage = pages[pages.length -
											1]
										let url = currentPage.route
										let options = currentPage.options
										let urlWithArgs = `/${url}?`
										for (let key in options) {
											let value = options[key]
											urlWithArgs += `${key}=${value}&`
										}
										let full_url = urlWithArgs.substring(0,
											urlWithArgs
											.length - 1) */

										let url = uni.getStorageSync('login_before_url');
										uni.reLaunch({
											url: url ? url : '/pages/index/index'
										})

										return;
									}
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

					}
				});
			},


			handleClick(e) {
				if (!this.show) return;
				const dataset = e.currentTarget.dataset;
				this.$emit('click', {
					index: Number(dataset.index)
				});
			},
			handleClickCancel() {
				if (!this.maskClosable) return;
				this.$emit('cancel');
			}
		}
	};
</script>

<style scoped>
	.tui-modal__container {
		width: 100%;
		height: 100%;
		position: fixed;
		left: 0;
		top: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		visibility: hidden;
	}

	.tui-modal-box {
		position: relative;
		opacity: 0;
		visibility: hidden;
		box-sizing: border-box;
		transition: all 0.3s ease-in-out;
	}

	.tui-modal-scale {
		transform: scale(0);
	}

	.tui-modal-normal {
		transform: scale(1);
	}

	.tui-modal-show {
		opacity: 1;
		visibility: visible;
	}

	.tui-modal-mask {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0, 0, 0, 0.6);
		transition: all 0.3s ease-in-out;
		opacity: 0;
		visibility: hidden;
	}

	.tui-mask-show {
		visibility: visible;
		opacity: 1;
	}

	.tui-modal-title {
		text-align: center;
		font-size: 34rpx;
		color: #333;
		padding-top: 20rpx;
		font-weight: bold;
	}

	.tui-modal-content {
		text-align: center;
		color: #999;
		font-size: 28rpx;
		padding-top: 20rpx;
		padding-bottom: 60rpx;
	}

	.tui-mtop {
		margin-top: 30rpx;
	}

	.tui-mbtm {
		margin-bottom: 30rpx;
	}

	.tui-modalBtn-box {
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.tui-flex-column {
		flex-direction: column;
	}

	.tui-modal-btn {
		width: 46%;
		height: 68rpx;
		line-height: 68rpx;
		position: relative;
		border-radius: 10rpx;
		font-size: 26rpx;
		overflow: visible;
		margin-left: 0;
		margin-right: 0;
	}

	.tui-modal-btn::after {
		content: ' ';
		position: absolute;
		width: 200%;
		height: 200%;
		-webkit-transform-origin: 0 0;
		transform-origin: 0 0;
		transform: scale(0.5, 0.5) translateZ(0);
		left: 0;
		top: 0;
		border-radius: 20rpx;
		z-index: 2;
	}

	.tui-btn-width {
		width: 80% !important;
	}

	.tui-primary {
		background: #5677fc;
		color: #fff;
	}

	.tui-primary-hover {
		background: #4a67d6;
		color: #e5e5e5;
	}

	.tui-primary-outline {
		color: #5677fc;
		background: transparent;
	}

	.tui-primary-outline::after {
		border: 1px solid #5677fc;
	}

	.tui-danger {
		background: #ed3f14;
		color: #fff;
	}

	.tui-danger-hover {
		background: #d53912;
		color: #e5e5e5;
	}

	.tui-danger-outline {
		color: #ed3f14;
		background: transparent;
	}

	.tui-danger-outline::after {
		border: 1px solid #ed3f14;
	}

	.tui-red {
		background: #e41f19;
		color: #fff;
	}

	.tui-red-hover {
		background: #c51a15;
		color: #e5e5e5;
	}

	.tui-red-outline {
		color: #e41f19;
		background: transparent;
	}

	.tui-red-outline::after {
		border: 1px solid #e41f19;
	}

	.tui-warning {
		background: #ff7900;
		color: #fff;
	}

	.tui-warning-hover {
		background: #e56d00;
		color: #e5e5e5;
	}

	.tui-warning-outline {
		color: #ff7900;
		background: transparent;
	}

	.tui-warning-outline::after {
		border: 1px solid #ff7900;
	}

	.tui-green {
		background: #19be6b;
		color: #fff;
	}

	.tui-green-hover {
		background: #16ab60;
		color: #e5e5e5;
	}

	.tui-green-outline {
		color: #19be6b;
		background: transparent;
	}

	.tui-green-outline::after {
		border: 1px solid #19be6b;
	}

	.tui-white {
		background: #fff;
		color: #333;
	}

	.tui-white-hover {
		background: #f7f7f9;
		color: #666;
	}

	.tui-white-outline {
		color: #333;
		background: transparent;
	}

	.tui-white-outline::after {
		border: 1px solid #333;
	}

	.tui-gray {
		background: #ededed;
		color: #999;
	}

	.tui-gray-hover {
		background: #d5d5d5;
		color: #898989;
	}

	.tui-gray-outline {
		color: #999;
		background: transparent;
	}

	.tui-gray-outline::after {
		border: 1px solid #999;
	}

	.tui-outline-hover {
		opacity: 0.6;
	}

	.tui-circle-btn {
		border-radius: 40rpx !important;
	}

	.tui-circle-btn::after {
		border-radius: 80rpx !important;
	}
</style>
