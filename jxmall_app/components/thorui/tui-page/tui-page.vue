<template>
	<view style="height: 100%;">
		<view :class="is_show_tabbar?'marginBottom':''">
			<slot></slot>
		</view>
		<tui-login-modal ref="loginModal" @cancel="cancelLogin" :show="showLoginModal"></tui-login-modal>
		<tui-modal :show="show_binding" :custom="true">
			<view>
				<view>
					<view>
						<view style="font-weight: bold;text-align: center;">授权提醒</view>
						<view style="text-align: center;color: #8D8A89;font-size: 10pt;">请授权手机号，以便为您提供更好的服务</view>
						<view class="flex-x-between" style="margin-top: 20rpx;">
							<button class="tui-modal-btn tui-red-outline tui-circle-btn" @click="cancel">
								取消
							</button>
							<button class="tui-modal-btn tui-red tui-circle-btn" open-type="getPhoneNumber"
								@getphonenumber="getPhoneNumber">
								授权手机号
							</button>
						</view>
					</view>
				</view>
			</view>
		</tui-modal>
		<tui-tabbar v-if="is_show_tabbar" :current="current" :selectedColor="color" :tabBar="list"></tui-tabbar>
		<tui-alert-adv v-if="showAlertAdv" :value="alertData"></tui-alert-adv>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				show_binding: false,
				showLoginModal: false,
				is_show_tabbar: false,
				alertData: null,
				current: 0,
				list: [],
				color: '',
				showAlertAdv: false
			}
		},
		mounted() {
			let pages = getCurrentPages();
			// 数组中索引最大的页面--当前页面
			let currentPage = pages[pages.length - 1];
			// 打印出当前页面中的 options
			if (currentPage.options && currentPage.options.scene) {
				let obj = this.$getScene(currentPage.options.scene);
				if (obj && obj.pid) {
					currentPage.options.pid = obj.pid;
				}
			}
			if (currentPage.options && currentPage.options.pid) {
				this.$http.setPid(currentPage.options.pid)
			}

			//#ifdef H5

			if (currentPage.$page.fullPath == '/') {
				currentPage.$page.fullPath = 'pages/index/index';
			}
			if (currentPage.$page.fullPath) {
				currentPage.$page.fullPath = '/' + currentPage.$page.fullPath
			}

			//#endif

			let tabbar = uni.getStorageSync('TABBAR');
			if (tabbar && tabbar.list) {
				for (let i in tabbar.list) {
					let item = tabbar.list[i];
					if (item.url == currentPage.$page.fullPath || currentPage.$page.fullPath.indexOf(item.url) != -1) {
						if (item.url == '/plugins/article/cat' && (currentPage.$page.fullPath.indexOf(
								'/plugins/article/cat-detail') != -1 || currentPage.$page.fullPath.indexOf(
								'/plugins/article/cat-info') != -1)) {
							continue;
						}
						this.is_show_tabbar = true;
						this.current = i;
					}
				}
				this.list = tabbar.list;
				this.color = tabbar.color;
			} else {
				this.$request({
					url: '/api/mall/tabbar'
				}).then(res => {
					if (res.code == 0) {
						let tabbar = res.data.tabbar;
						if (tabbar && tabbar.list) {
							uni.setStorageSync('TABBAR', res.data.tabbar);
							tabbar.list.forEach((item, i) => {
								if (item.url == currentPage.$page.fullPath || currentPage.$page
									.fullPath
									.indexOf(item.url) != -1) {
									this.is_show_tabbar = true;
									this.current = i;
								}
							})
							this.list = tabbar.list;
							this.color = tabbar.color;
						}
					}
				})
			}



			setTimeout(() => {
				let alert_data = uni.getStorageSync('ALERT_DATA');


				if (alert_data) {
					if (alert_data.show_time == 0) {
						let day = new Date();
						day.setTime(day.getTime());
						let s2 = day.getFullYear() + "-" + (day.getMonth() + 1) + "-" + day.getDate();
						let alert_date = uni.getStorageSync('ALERT_DATE');
						if (!alert_date || alert_date == null || alert_date == '') {
							uni.setStorageSync('ALERT_DATE', s2);
							this.alertData = alert_data;
							this.showAlertAdv = true;
						}
					}
					if (alert_data.show_time == 1) { //每次
						let launch_show = uni.getStorageSync('ALERT_LAUNCH_SHOW');
						if (launch_show == 0) {
							uni.setStorageSync('ALERT_LAUNCH_SHOW', 1);
							this.alertData = alert_data;
							this.showAlertAdv = true;
						} else {
							this.showAlertAdv = false;
						}
					}
				}
			}, 500)

			if (this.$http.getToken() == null || this.$http.getToken() == "" || !this.$http.getToken()) {
				if (this.current != 0) {
					this.showLoginModalWindow();
				}
				if (this.current == 0) {
					if (this.$util.getSetting('is_home_login') == 1) {
						this.showLoginModalWindow();
					}
				}
			}
		},
		created() {

			//#ifdef H5
			if (this.$wechatSdk.isWechat()) {
				this.$wechatSdk.share();
			}
			//#endif
			this.checkBinding();

		},
		methods: {
			cancel() {
				this.show_binding = false;
			},
			getPhoneNumber(e) {
				console.log(e)
				uni.showLoading({
					title: '请等待',
					mask: false
				})
				const encryptedData = e.detail.encryptedData
				const iv = e.detail.iv
				if (e.detail.errMsg == "getPhoneNumber:ok") {
					this.login(encryptedData, iv)
				} else {
					uni.showToast({
						title: '请重新获取',
						icon: "none"
					})
				}
			},

			checkBinding() {
				this.$request({
					url: "/api/user/check-binding"
				}).then(res => {
					if (res.code == 0) {
						if (res.data.need_bind == 1) {
							this.show_binding = true;
							console.log('需要绑定');
						}
					}
				})
			},
			login(encryptedData, iv) {
				uni.hideLoading()

				uni.login({
					success: (e) => {
						this.$request({
							url: '/api/user/encrypted',
							method: "POST",
							data: {
								code: e.code,
								encryptedData: encryptedData,
								iv: iv,
							}
						}).then(res => {
							if (res.code == 0) {
								if (res.data.data.phoneNumber) {
									this.$request({
										url: "/api/user/binding",
										data: {
											mobile: res.data.data.phoneNumber
										},
										method: 'post'
									}).then(e => {
										if (e.code == 0) {
											this.show_binding = false;
										}
										uni.showToast({
											title: e.msg
										})
									})
								}
							} else {
								uni.showToast({
									title: res.msg
								})
							}
						})
					}
				})
			},
			cancelLogin(e) {
				this.showLoginModal = false;
			},
			showLoginModalWindow() {
				this.showLoginModal = true;
				this.$refs.loginModal.showLoginModal();

			},
		},
	}
</script>

<style>
	.marginBottom {
		padding-bottom: 150rpx;
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

	.tui-red {
		background: #F1430D;
		color: #fff;
	}

	.tui-red-hover {
		background: #F1430D;
		color: #e5e5e5;
	}

	.tui-red-outline {
		color: #F1430D;
		background: transparent;
	}

	.tui-red-outline::after {
		border: 1px solid #F1430D;
	}
</style>
