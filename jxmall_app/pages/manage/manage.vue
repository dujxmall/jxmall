<template>
	<tui-page v-if="setting">

		<image :src="setting.header.user_bg" class="tui-my-bg" style="height: 450rpx;"></image>
		<view class="tui-mybg-box" style="height: 250rpx;color: #FFF;font-size: 11pt;position: absolute;top: 0;padding: 0 30rpx;">
			<view style="padding-top: 120rpx;" class="flex-x-between" v-if="info">
				<view class="flex-y-center">
					<image style="width: 90rpx;height: 90rpx;border-radius: 50%;" :src="info.avatar_url">
					</image>
					<view style="padding: 0 20rpx;">
						<view>{{info.nickname}}</view>
						<view style="font-size: 9pt;">{{setting.header.parent_label}}: {{info.parent_name}} </view>
					</view>
				</view>
				<view style="margin-right: 40rpx;">
					<image style="width: 60rpx;height: 60rpx;margin-right: 20rpx;" @click="showPoster" :src="setting.header.qrcode">
					</image>
				</view>
			</view>

			<view style="margin-top: 30rpx;" v-if="info">
				<tui-grid style="background:none;font-size: 9pt;">
					<tui-grid-item :backgroundColor="''" :cell="'3'" class="item">
						<view>
							<view style="font-size: 11pt;">{{info.money}}</view>
							<view style="padding: 10rpx 0;">{{setting.header.balance.name}}</view>
						</view>
					</tui-grid-item>
					<tui-grid-item :backgroundColor="''" :cell="'3'" class="item">
						<view>
							<view style="font-size: 11pt;">{{info.price}}</view>
							<view style="padding: 10rpx 0;">{{setting.header.price.name}}</view>
						</view>
					</tui-grid-item>
					<tui-grid-item :backgroundColor="''" :cell="'3'" class="item">
						<view>
							<view style="font-size: 11pt;">{{info.integral}}</view>
							<view style="padding: 10rpx 0;">{{setting.header.integral.name}}</view>
						</view>
					</tui-grid-item>
				</tui-grid>
			</view>
		</view>
		<view style="width: 100%;position: relative;margin-bottom: 20rpx;z-index: 999;" v-if="info">
			<view style="width: 100%;position: absolute; top: -80rpx;">
				<view style="background-color: #FFFFFF;width: 95%;margin: 0 auto;border-radius: 10rpx;margin-bottom: 20rpx;overflow: hidden;">
					<view style="font-size: 9pt;border-bottom: solid #f0f0f0 1rpx;font-weight: bold;padding: 20rpx;" class="flex-y-center">
						<text>{{setting.finance.name}}</text>
					</view>
					<tui-grid style="font-size: 9pt;padding-top: 5rpx">
						<tui-grid-item :cell="'4'" class="item">
							<view>
								<view style="font-size: 11pt;">{{info.total_price}}</view>
								<view style="padding-top: 10rpx;">{{setting.finance.total.name}}</view>
							</view>
						</tui-grid-item>
						<tui-grid-item :cell="'4'" class="item">
							<view>
								<view style="font-size: 11pt;">{{info.price}}</view>
								<view style="padding-top: 10rpx;">{{setting.finance.price.name}}</view>
							</view>
						</tui-grid-item>
						<tui-grid-item :cell="'4'" class="item">
							<view>
								<view style="font-size: 11pt;">{{info.cashing}}</view>
								<view style="padding-top: 10rpx;">{{setting.finance.cashing.name}}</view>
							</view>
						</tui-grid-item>
						<tui-grid-item :cell="'4'" class="item">
							<view>
								<view style="font-size: 11pt;">{{info.cash}}</view>
								<view style="padding-top: 10rpx;">{{setting.finance.cash.name}}</view>
							</view>
						</tui-grid-item>
					</tui-grid>
				</view>
				<view style="background-color: #FFFFFF;width: 95%;margin: 0 auto;border-radius: 10rpx;overflow: hidden;margin-bottom: 20rpx;">
					<view style="font-size: 9pt;border-bottom: solid #f0f0f0 1rpx;padding: 20rpx;font-weight: bold;">
						<text>{{setting.menus.name}}</text>
					</view>
					<tui-grid style="font-size: 8pt;">
						<tui-grid-item :cell="'4'" class="item" @click="pageTo(menu)" v-if="setting.menus.list.length" v-for="menu in setting.menus.list">
							<view>
								<view>
									<image :src="menu.icon" mode="aspectFill" style="width: 60rpx;height: 60rpx;"></image>
								</view>
								<view style="padding: 10rpx 0;">{{menu.name}}</view>
							</view>
						</tui-grid-item>

					</tui-grid>
				</view>

			</view>
		</view>
		<tui-modal-poster :url="poster_url" :showPoster="showPosterModal" @closePoster="closePoster"></tui-modal-poster>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				poster_url: '',
				showPosterModal: false,
				info: null,
				setting: {
					header: {
						parent_label: '',
						user_bg: '',
						avatar: '',
						qrcode: '',
						balance: {
							name: '余额'
						},
						price: {
							name: '佣金'
						},
						integral: {
							name: '积分'
						},
					},
					menus: {
						type: 0,
						name: '常用工具',
						list: [{
							url: '',
							icon: '',
							name: '示例'
						}, ],
					},
					finance: {
						name: '我的资产',
						total: {
							name: '积分',
						},
						price: {
							name: '佣金',

						},
						cashing: {
							name: '佣金',
						},
						cash: {
							name: '成功提现',

						},
					},

				},
			}
		},
		onShow() {

			this.getSetting()
		},
		methods: {
			closePoster(){
				this.showPosterModal=false;
				
			},
			showPoster() {
				this.$request({
					url: '/api/poster/share-poster',

					method: 'get'
				}).then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						this.poster_url = res.data.url
						this.showPosterModal = !this.showPosterModal;
					}
				})
			},
			pageTo(e) {
				uni.navigateTo({
					url: e.url
				})
			},
			getSetting() {

				uni.showLoading({
					title: '加载中'
				})
				this.$request({
					url: "/api/manage/info"
				}).then(res => {

					uni.hideLoading()
					if (res.code == 0) {
						this.setting = res.data.setting;
						this.info = res.data.info;
					}
					console.log(res);

				})


			},


		}

	}
</script>

<style lang="scss">
	.item {

		overflow: hidden;
		box-sizing: border-box;
		background: none;
		text-align: center;
		padding: 0;
	}

	.tui-mybg-box {
		width: 100%;
		height: 464rpx;
		position: relative;
	}

	.tui-my-bg {
		width: 100%;
		height: 250rpx;
		display: block;
	}

	.tui-header-center {
		position: absolute;
		width: 100%;
		height: 128rpx;
		left: 0;
		top: 120rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
	}
</style>
