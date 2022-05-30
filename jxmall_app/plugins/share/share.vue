<template>
	<view v-if="info">

		<image :src="info.user_bg" class="tui-my-bg" style="height: 450rpx;"></image>
		<view class="tui-mybg-box" style="height: 250rpx;color: #FFF;font-size: 11pt;position: absolute;top: 0;padding: 0 30rpx;">
			<view style="padding-top: 120rpx;" class="flex-x-between">
				<view class="flex-y-center">
					<image style="width: 90rpx;height: 90rpx;border-radius: 50%;" :src="info.avatar_url">
					</image>
					<view style="padding: 0 20rpx;">
						<view>{{info.nickname}}</view>
						<view style="font-size: 9pt;">上级: {{info.parent_name}} </view>
						<view style="font-size: 9pt;">等级:{{info.level_name}}</view>
					</view>
				</view>
				<view>
					<!-- 	<image style="width: 60rpx;height: 60rpx;margin-right: 20rpx;" :src="setting.header.qrcode">
					</image> -->
				</view>
			</view>
		</view>
		<view style="width: 100%;position: relative;margin-bottom: 20rpx;z-index: 999;">
			<view style="width: 100%;position: absolute; top: -160rpx;">
				<view style="background-color: #FFFFFF;width: 95%;margin: 0 auto;border-radius: 10rpx;margin-bottom: 20rpx;overflow: hidden;">
					<view style="font-size: 9pt;border-bottom: solid #f0f0f0 1rpx;font-weight: bold;padding: 20rpx;" class="flex-y-center">
						<text>推广数据</text>
					</view>
					<tui-grid style="font-size: 9pt;padding-top: 5rpx">
						<tui-grid-item :cell="'4'" class="item">
							<view>
								<view style="font-size: 11pt;">{{info.share_order_count}}</view>
								<view style="padding-top: 10rpx;">分销订单</view>
							</view>
						</tui-grid-item>
						<tui-grid-item :cell="'4'" class="item">
							<view>
								<view style="font-size: 11pt;">{{info.total_price}}</view>
								<view style="padding-top: 10rpx;">分销佣金</view>
							</view>
						</tui-grid-item>
						<tui-grid-item :cell="'4'" class="item">
							<view>
								<view style="font-size: 11pt;">{{info.first_child_count}}</view>
								<view style="padding-top: 10rpx;">直推人数</view>
							</view>
						</tui-grid-item>
						<tui-grid-item :cell="'4'" class="item">
							<view>
								<view style="font-size: 11pt;">{{info.child_count}}</view>
								<view style="padding-top: 10rpx;">团队人数</view>
							</view>
						</tui-grid-item>
					</tui-grid>
				</view>
				<view style="background-color: #FFFFFF;width: 95%;margin: 0 auto;border-radius: 10rpx;overflow: hidden;margin-bottom: 20rpx;">
					<view style="font-size: 9pt;border-bottom: solid #f0f0f0 1rpx;padding: 20rpx;font-weight: bold;">
						<text>佣金记录</text>
					</view>

					<view style="width: 100%;position: relative;">
						<tui-tabs style="width: 100%;" :tabs="tabs2" :currentTab="currentTab" selectedColor="#EB0909" sliderBgColor="#EB0909"
						 @change="change"></tui-tabs>
						<view style="padding:20rpx;font-size: 9pt;">
							<view class="flex-x-between" style="border-bottom: solid #F3F3F3 1rpx;padding: 20rpx;" v-if="list.length" v-for="log in list">
								<view class="flex-y-center">
									<view>
										<image :src="log.avatar_url" style="width: 60rpx;height: 60rpx;border-radius: 50%;"></image>
										<view>{{log.nickname}}</view>
									</view>

									<view style="padding: 0 10rpx;">

										<view>
											<view>{{log.common_order_no}}</view>
											<view class="flex-y-center">
												<image :src="log.goods.cover_pic" style="width: 60rpx;height: 60rpx;border-radius: 50%;"></image>
												<view>
													<view style="font-weight: bold;">{{log.goods.name}}</view>
													<view style="font-size: 8pt;">
														x {{log.num}}
													</view>
													<view style="font-size: 8pt;color: #B2B2B2;">
														<view>创建时间：{{log.created_at}}</view>
														<view>状态更新：{{log.updated_at}}</view>
													</view>
												</view>
											</view>

										</view>

									</view>
								</view>
								<view>
									<text style="color:#EE4521;" v-if="log.status==1">+</text> <text style="color:#EE4521;">{{log.price}}</text>
								</view>
							</view>
							<block v-if="list.length==0&&!loading">
								<tui-no-data :fixed="false" imgUrl="/static/images/toast/img_nodata.png">暂无佣金记录~</tui-no-data>
							</block>

						</view>

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
				currentTab: 0,
				status: -1,
				page: 1,
				list: [],
				tabs2: [{
					name: "全部"
				}, {
					name: "待结算"
				}, {
					name: "已结算"
				}, {
					name: "无效",
				}],
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
		created() {
			this.getInfo();

			this.getList();
		},
		onShow() {
			this.getSetting()
		},
		onReachBottom() {
			this.getList();
		},
		methods: {
			change(e) {
				this.currentTab = e.index
				this.status = this.currentTab - 1;
				this.page = 1;
				this.is_no_more = false;
				this.list = [];
				this.getList();

			},
			pageTo(e) {
				uni.navigateTo({
					url: e.url
				})
			},
			getList() {
				if (this.is_no_more) {
					uni.showToast({
						title: '没有更多数据'
					})
					return;
				}
				this.$request({
					url: "/plugin/share/api/share/price-log",
					data: {
						page: this.page,
						status: this.status
					}
				}).then(res => {
					if (res.code == 0) {
						if (this.page == 1) {
							this.list = res.data.list
						}
						if (this.page > 1) {
							this.list = this.list.concat(res.data.list)
						}
						if (res.data.pagination.page_count > this.page) {
							this.page++;
						} else {
							this.is_no_more = true;
						}

					}
				})

			},

			getInfo() {
				this.$request({
					url: '/plugin/share/api/share/info'
				}).then(res => {
					if (res.code == 0) {
						this.info = res.data.info;
					}

					if (res.code == 1) {
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: function(e) {
								uni.navigateBack({
									delta: 1
								})
							}
						})
					}
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
						//this.info = res.data.info;
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
