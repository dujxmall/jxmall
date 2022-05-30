<template>
	<tui-page class="container">
		<tui-diy-view :value="pageData"></tui-diy-view>
		
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				not_home_page: false,
				pageData: [],
				setting: null
			}
		},
		onLoad(e) {
			// 获取当前小程序的页面栈
			let indexData = uni.getStorageSync('INDEX_DATA');
			if (indexData) {
				this.pageData = indexData;
			}
			this.getIndexData();
			this.getMallSetting();
		},
		onShow: function() {
			var updateManager = uni.getUpdateManager();
			updateManager.onCheckForUpdate(function(res) {
				if (res.hasUpdate == true) {
					updateManager.onUpdateReady(function(res) {
						uni.showModal({
							title: '更新提示',
							content: '新版本已经准备好，是否重启应用？',
							success(res) {
								console.log(res, '新版本下载完')
								if (res.confirm) {
									// 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
									updateManager.applyUpdate();
								}
							}
						});
					})
				}
			})
			updateManager.onUpdateFailed(function(res) {
				// 新的版本下载失败
				uni.showModal({
					title: '更新失败',
					content: '很抱歉未能更新成功。您可以尝试重新进入，或者在微信界面下拉，在最近使用中找到此小程序，长按将其拖拽到底部删除按钮中，然后重新搜索小程序进入。给您带来的不便，深表歉意！',
					showCancel: false,
				});
			});
		},
		onShareAppMessage: function(e) {
			let user = uni.getStorageSync('USER');
			if (user) {
				return {
					path: 'pages/index/index?pid=' + user.id,
				}
			}
			return {
				path: 'pages/index/index',
			}
		},
		methods: {
			getMallSetting() {
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
			},
			getIndexData() {
				this.$request({
					url: "/api/index/index"
				}).then(res => {
					if (res.code == 0) {
						uni.setStorageSync('INDEX_DATA', res.data.page_data)
						this.pageData = res.data.page_data
						uni.setNavigationBarTitle({
							title:res.data.name
						})
					} else {
						uni.showToast({
							title: res.msg
						})
						this.not_home_page = true;
					}
				})
			},
			login() {
				uni.navigateTo({
					url: '/pages/login/login'
				})
			},
			selected(e) {


			}
		}
	}
</script>

<style lang="scss">
	.tui-title {
		padding: 50rpx 30rpx 30rpx 30rpx;
		font-size: 32rpx;
		color: #333;
		box-sizing: border-box;
		font-weight: bold;
		clear: both;
	}

	.tui-grid-icon {
		width: 64rpx;
		height: 64rpx;
		margin: 0 auto;
		text-align: center;
		vertical-align: middle;
	}

	.nav {
		text-align: center;
		background-color: #FFFFFF;

		.icon {

			width: 87rpx;
			height: 87rpx;
		}

		.name {
			display: block;
			text-align: center;
			font-weight: 400;
			color: #333;
			font-size: 13px;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
	}

	.banner-swiper {
		width: 100%;
		height: 370rpx;
		border-radius: 12rpx;
		overflow: hidden;
		transform: translateY(0);
	}

	.tui-banner-box {
		width: 100%;
		padding: 0 20rpx;
		box-sizing: border-box;

		/* overflow: hidden; */
		z-index: 99;
		left: 0;
	}

	.tui-slide-image {
		width: 100%;
		height: 100%;
		display: block;
	}
</style>
