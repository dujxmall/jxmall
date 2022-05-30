<template>
	<tui-page ref="page" class="app">
		<view v-if="order">
			<view class="price-box">
				<text>支付金额</text>
				<view style="font-size: 20pt;color: #000000;">
					<text style="font-size: 12pt;">￥</text> {{order.pay_price}}
				</view>
			</view>
			<view class="pay-type-list" v-if="payment">
				<view class="type-item b-b" style="border-bottom: solid #f0f0f0 1rpx" v-if="payment.wechat_pay"
					@click="changePayType(payment.wechat_pay.payment)">
					<image :src="payment.wechat_pay.icon" mode="aspectFill" style="height: 64rpx;width: 64rpx;"></image>
					<view class="con" style="font-size: 11pt;padding: 10rpx;">
						<view class="tit">{{payment.wechat_pay.payment_name}}</view>
						<view style="font-size: 9pt;">推荐使用微信支付</view>
					</view>
					<label class="radio">
						<radio value="" color="#EB0909" :checked='select_payment ==  payment.wechat_pay.payment' />
						</radio>
					</label>
				</view>
				<view class="type-item b-b" v-if="payment.balance_pay"
					@click="changePayType(payment.balance_pay.payment)">
					<image :src="payment.balance_pay.icon" mode="aspectFill" style="height: 64rpx;width: 64rpx;">
					</image>
					<view class="con" style="font-size: 11pt;padding: 10rpx;">
						<view class="tit">{{payment.balance_pay.payment_name}}</view>
						<view style="font-size: 9pt;">可用余额 ¥ {{balance}}</view>
					</view>
					<label class="radio">
						<radio value="" color="#EB0909" :checked='select_payment == payment.balance_pay.payment' />
						</radio>
					</label>
				</view>
			</view>
			<view class="flex-x-center" style="margin-top: 100rpx;">
				<tui-button :width="'600rpx'" height="88rpx" type="danger" shape="circle" shadow @click="confirm">确认支付
				</tui-button>
			</view>

		</view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				union_order_id: '',
				common_order_id: '',
				order: null,
				payment: null,
				payType: 1,
				balance: '',
				select_payment: '',
				is_paying: false,
			}
		},
		onLoad: function(options) {
			if (options.union_order_id) {
				this.union_order_id = options.union_order_id
			}
			if (options.common_order_id) {
				this.common_order_id = options.common_order_id
			}
			this.getPayData();
		},
		methods: {
			confirm() {
				let that = this;
				if (that.is_paying) {
					return;
				}
				that.is_paying = true;

				this.$request({
					url: '/api/order/pay-data',
					data: {
						pay_type: this.select_payment,
						union_order_id: this.union_order_id,
						common_order_id: this.common_order_id
					},
					method: "post"
				}).then(res => {
					if (res.code == 0) {
						if (this.payment.balance_pay && this.select_payment == this.payment.balance_pay.payment) {
							that.is_paying = false;
							uni.showModal({
								title: '提示',
								content: res.msg,
								success: function(e) {
									uni.reLaunch({
										url: '/pages/user/user'
									})
								}
							})
						}
						if (this.payment.wechat_pay && this.select_payment == this.payment.wechat_pay.payment) {
							//#ifdef H5
							if (this.$wechatSdk.isWechat()) {
								this.$wechatSdk.pay(res.data.pay_data).then(res => {
									if (res.code == 1) {
										uni.showModal({
											title: '提示',
											content: res.msg
										})
										return;
									}
									that.is_paying = false;
									uni.showLoading({
										title: '正在完成支付...'
									})
									setTimeout(() => {
										that.$request({
											url: "/api/order/query-pay",
											data: {
												union_order_id: that.union_order_id,
												common_order_id: that.common_order_id
											}
										}).then(res => {
											uni.hideLoading();
											uni.showModal({
												title: '提示',
												content: '支付成功',
												success: function(e) {
													uni.reLaunch({
														url: '/pages/user/user'
													})
												}
											})
										})
									}, 3000)
								}).catch(e => {
									that.is_paying = false;
									uni.showModal({
										title: '提示',
										content: e.msg,
										success: function(e) {
											uni.reLaunch({
												url: '/pages/user/user'
											})
										}
									})
								})
							}
							//#endif
							//#ifdef APP-PLUS
							let data = res.data.pay_data;
							let orderInfo = {
								appid: data.appId,
								noncestr: data.noncestr,
								package: 'Sign=WXPay',
								prepayid: data.prepayid,
								timestamp: data.timestamp,
								partnerid: data.partnerid,
								sign: data.sign,
							}
							uni.requestPayment({
								provider: 'wxpay',
								orderInfo: orderInfo,
								success: function(e) {
									that.is_paying = false;
									uni.showLoading({
										title: '正在完成支付...'
									})
									setTimeout(() => {
										that.$request({
											url: "/api/order/query-pay",
											data: {
												union_order_id: that.union_order_id,
												common_order_id: that.common_order_id
											}
										}).then(res => {
											uni.hideLoading();
											uni.showModal({
												title: '提示',
												content: '支付成功',
												success: function(e) {
													uni.reLaunch({
														url: '/pages/user/user'
													})
												}
											})
										})
									}, 3000)
								},
								fail: function(e) {
									that.is_paying = false;
									console.log(e);
									uni.showModal({
										title: '提示',
										content: '支付失败',
										success: function(e) {
											uni.reLaunch({
												url: '/pages/user/user'
											})
											/* uni.redirectTo({
												url: "/pages/order/order"
											}) */
										}
									})
								}
							})

							//#endif
							//#ifdef MP-WEIXIN
							let data = res.data.pay_data;
							uni.requestPayment({
								provider: 'wxpay',
								appId: data.appId,
								nonceStr: data.nonceStr,
								package: data.package,
								signType: data.signType,
								paySign: data.paySign,
								timeStamp: data.timestamp,
								success: function(e) {
									that.is_paying = false;
									uni.showLoading({
										title: '正在完成支付...'
									})
									setTimeout(() => {
										that.$request({
											url: "/api/order/query-pay",
											data: {
												union_order_id: that
													.union_order_id,
												common_order_id: that
													.common_order_id
											}
										}).then(res => {
											uni.hideLoading();
											uni.showModal({
												title: '提示',
												content: '支付成功',
												success: function(e) {
													uni.reLaunch({
														url: '/pages/user/user'
													})
												}
											})
										})
									}, 3000)
								},
								fail: function(e) {
									console.log(e);
									that.is_paying = false;
									uni.showModal({
										title: '提示',
										content: '支付失败',
										success: function(e) {
											uni.reLaunch({
												url: '/pages/user/user'
											})
											/* uni.redirectTo({
												url: "/pages/order/order"
											}) */
										}
									})
								}
							})

							//#endif

						}
						return;
					}

					if (res.code == -1) {
						this.$refs.page.showLoginModalWindow();
						return;
					}
					if (res.code == 1) {
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: function(e) {
								uni.redirectTo({
									url: "/pages/order/order"
								})

							}
						})
						return;
					}
				})

			},
			changePayType(type) {
				this.select_payment = type;
			},
			getPayData() {
				uni.showLoading({
					title: '加载中...'
				})
				this.$request({
					url: '/api/order/pay-data',
					data: {
						union_order_id: this.union_order_id,
						common_order_id: this.common_order_id
					}
				}).then(res => {
					uni.hideLoading();
					if (res.code == -1) {
						this.$refs.page.showLoginModalWindow();
						return;
					}
					if (res.code == 0) {
						this.payment = res.data.payment;
						this.order = res.data.order_info;
						this.balance = res.data.balance;
						if (this.payment && this.payment.wechat_pay) {
							this.select_payment = this.payment.wechat_pay.payment
						}
					}
					if (res.code == 1) {
						if (res.data.is_pay == 1) {
							uni.showModal({
								title: '提示',
								content: res.msg,
								success: (e) => {
									uni.redirectTo({
										url: '/pages/order/order'
									})
								}
							})
						} else {
							uni.showModal({
								title: '提示',
								content: res.msg,
								success: (e) => {
									uni.navigateBack({
										delta: 1
									})
								}
							})
						}
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	.app {
		width: 100%;
	}

	.price {

		font-size: 50px !important;
	}

	.price-box {
		background-color: #fff;
		height: 265upx;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		font-size: 15pt;
		color: #909399;


	}

	.pay-type-list {
		margin-top: 20upx;
		background-color: #fff;
		padding: 0 30rpx;

		.type-item {
			height: 120upx;
			padding: 20upx 0;
			display: flex;
			justify-content: space-between;
			align-items: center;

			font-size: 30upx;
			position: relative;
		}

		.icon {
			width: 100upx;
			font-size: 52upx;
		}

		.icon-erjiye-yucunkuan {
			color: #fe8e2e;
		}

		.icon-weixinzhifu {
			color: #36cb59;
		}

		.icon-alipay {
			color: #01aaef;
		}

		.tit {
			font-size: 32upx;
			color: #303133;
			margin-bottom: 4upx;
		}

		.con {
			flex: 1;
			display: flex;
			flex-direction: column;
			font-size: 10pt;
			color: #909399;
		}
	}

	.mix-btn {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 630upx;
		height: 80upx;
		margin: 80upx auto 30upx;
		font-size: 32upx;
		color: #fff;
		background-color: #EB0909;
		border-radius: 10upx;
		box-shadow: 1px 2px 5px rgba(219, 63, 96, 0.4);
	}
</style>
