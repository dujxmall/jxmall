<template>
	<tui-page>
		<view class="container" v-if="order">
			<view class="tui-order-header" @tap="switchStatus">
				<!-- <image :src="webURL+'img_detail_bg.png'" mode="widthFix" class="tui-img-bg"></image> -->
				<view class="tui-header-content">
					<view>
						<view class="tui-status-text">{{getStatusText(status)}}</view>
						<!-- <view class="tui-reason"><text class="tui-reason-text">{{getReason(status)}}</text>
							<tui-countdown :time="1800" color="rgba(254,254,254,0.75)" colonColor="rgba(254,254,254,0.75)" borderColor="transparent"
							 backgroundColor="transparent" v-if="status===1"></tui-countdown>
						</view> -->
					</view>
					<image :src="getImg(status)" class="tui-status-img" mode="widthFix"></image>
				</view>
			</view>

			<tui-list-cell unlined :hover="false">
				<view class="tui-flex-box">
					<image src="/static/order/img_order_address3x.png" class="tui-icon-img"></image>
					<view class="tui-addr">
						<view class="tui-addr-userinfo">{{order.name}}<text class="tui-addr-tel">{{order.mobile}}</text>
						</view>
						<view class="tui-addr-text">{{order.address}}</view>
					</view>
				</view>
			</tui-list-cell>

			<view class="tui-order-item">
				<tui-list-cell :hover="false" :lineLeft="false">
					<view class="tui-goods-title">
						商品信息
					</view>
				</tui-list-cell>
				<block v-if="list" v-for="(item,index) in list" :key="index">
					<tui-list-cell padding="0">
						<view class="tui-goods-item">
							<image :src="item.goods.cover_pic" class="tui-goods-img"></image>
							<view class="tui-goods-center">
								<view class="tui-goods-name">{{item.goods.name}}</view>
								<view class="tui-goods-attr" v-if="item.goods.is_attr==0">规格：默认</view>
								<view class="tui-goods-attr" v-if="item.goods.is_attr==1&&item.attr.length>0"
									v-for="attr in item.attr">{{attr.attr_group_name}}:{{attr.attr_name}}</view>
							</view>
							<view class="tui-price-right">
								<view>￥{{item.price}}</view>
								<view>x{{item.num}}</view>
								<block v-if="setting&&setting.is_ban_cancel!=1">
									<view style="margin-top: 30rpx;" v-if="order.status==2">
										<tui-button type="danger" :size="20" plain shape="circle" height="50rpx"
											width="120rpx" @click="refund(item)">退换货</tui-button>

									</view>
								</block>
							</view>

						</view>
					</tui-list-cell>
				</block>
				<view class="tui-goods-info">

					<view class="tui-price-flex  tui-size24">
						<view>优惠券</view>
						<view>￥{{order.coupon_cut_price}}</view>
					</view>
					<view class="tui-price-flex  tui-size24">
						<view>配送费</view>
						<view>￥{{order.express_price}}</view>
					</view>
					<view class="tui-price-flex tui-size32 tui-pbtm20">
						<view class="tui-flex-shrink">合计</view>
						<view class="tui-goods-price">
							<view class="tui-size-24">￥</view>
							<view class="tui-price-large">{{order.total_price}}</view>

						</view>
					</view>
					<view class="tui-price-flex tui-size32">
						<view class="tui-flex-shrink">实付款</view>
						<view class="tui-goods-price tui-primary-color">
							<view class="tui-size-24">￥</view>
							<view class="tui-price-large">{{order.pay_price}}</view>

						</view>
					</view>
				</view>
			</view>

			<view class="tui-order-info">
				<tui-list-cell :hover="false">
					<view class="tui-order-title">
						订单信息
					</view>
				</tui-list-cell>
				<view class="tui-order-content">
					<view class="tui-order-flex">
						<view class="tui-item-title">订单号:</view>
						<view class="tui-item-content">{{order.order_no}}</view>
					</view>

					<view class="tui-order-flex">
						<view class="tui-item-title">创建时间:</view>
						<view class="tui-item-content">{{order.created_at}}</view>
					</view>
					<view class="tui-order-flex" v-if="order.is_pay==1">
						<view class="tui-item-title">付款时间:</view>
						<view class="tui-item-content">{{order.pay_time}}</view>
					</view>

					<view class="tui-order-flex">
						<view class="tui-item-title">配送方式:</view>
						<view class="tui-item-content">快递</view>
					</view>
					<view class="tui-order-flex">
						<view class="tui-item-title">订单备注:</view>
						<view class="tui-item-content" v-if="order.remarks">{{order.remarks}}</view>
						<view class="tui-item-content" v-else>无备注</view>
					</view>
				</view>
			</view>


			<view class="tui-order-info" v-if="express_detail&&express_detail.code==0">
				<tui-list-cell :hover="false">
					<view class="tui-order-title">
						物流跟踪 <text style="color: #ff4455;">({{express_detail.msg}})</text>
					</view>
				</tui-list-cell>
				<view class="tui-order-content">
					<view v-if="express_detail.data.list&&express_detail.data.list.length"
						style="padding-bottom: 20rpx;" v-for="item in express_detail.data.list">
						<view style="color: #ff4455;padding: 10rpx 0;">{{item.AcceptTime}}</view>
						<view>
							{{item.AcceptStation}}
						</view>

					</view>


				</view>
			</view>

			<view class="tui-safe-area"></view>
			<view class="tui-tabbar tui-order-btn">
				<view class="tui-btn-mr" v-if="order.status==4||order.status==5">
					<tui-button type="black" :plain="true" width="152rpx" height="56rpx" :size="26" shape="circle"
						@click="orderDelete">删除订单</tui-button>
				</view>
				<view class="tui-btn-mr flex-row" v-if="order.status!=5">
					<tui-button type="danger" :plain="true" width="152rpx" height="56rpx" :size="26" shape="circle"
						@click="btnPay" v-if="order.is_pay==0">立即支付</tui-button>
					<tui-button type="danger" :plain="true" width="152rpx" height="56rpx" :size="26" shape="circle"
						@click="confirm" v-if="order.is_pay==1&&order.is_send==1&&order.is_confirm==0">确认收货
					</tui-button>
					<view style="margin: 0 20rpx;">
						<tui-button type="danger" width="152rpx" height="56rpx" :size="26" shape="circle"
							@click="applySend" v-if="order.is_pay==1&&order.is_send==0&&order.is_apply_send==0">申请发货
						</tui-button>
					</view>
					<tui-button type="danger" :plain="true" width="152rpx" height="56rpx" :size="26" shape="circle"
						v-if="order.is_pay==1&&order.is_send==0">等待发货</tui-button>
					<tui-button style="margin-left: 20rpx;" type="danger" width="152rpx" height="56rpx" :size="26"
						shape="circle" @click="getExpress" v-if="order.is_pay==1&&order.is_send==1">查询物流
					</tui-button>
				</view>
				<block v-if="setting&&setting.is_ban_cancel!=1">
					<view class="tui-btn-mr" v-if="order.status!=5">
						<tui-button type="danger" :plain="true" width="152rpx" height="56rpx" :size="26" shape="circle"
							v-if="order.is_pay==1&&order.is_send==0" @click="orderCancel">取消</tui-button>
					</view>
				</block>
			</view>
			<t-pay-way :show="show" @close="popupClose"></t-pay-way>
		</view>
	</tui-page>
</template>

<script>
	import tPayWay from "@/components/views/t-pay-way/ti-pay-way"
	export default {
		components: {
			tPayWay
		},
		data() {
			return {
				//1-待付款 2-付款成功 3-待收货 4-订单已完成 5-交易关闭
				status: 1,
				show: false,
				order_id: '',
				order: null,
				list: [],
				express_detail: null,
				common_order: null,
				setting: null,
			}
		},
		onLoad: function(options) {
			if (!options.order_id) {
				uni.navigateBack({
					delta: 1
				})
				return;
			}
			this.setting = uni.getStorageSync('MALL_SETTING');

			this.order_id = options.order_id
			this.getDetail();
		},
		methods: {
			applySend() {
				this.$request({
					url: "/api/order/apply-send",
					data: {
						order_id: this.order_id
					},
					method: 'post'
				}).then(e => {
					if (e.code == 0) {
						uni.showModal({
							title: '提示',
							content: e.msg,
							success: (e) => {
								this.getDetail();
							}
						})
					}
				})
			},
			getExpress() {
				uni.showLoading({
					title: '正在查询,请等待...'
				})
				this.$request({
					url: "/api/order/express",
					data: {
						order_id: this.order_id
					}
				}).then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						this.express_detail = res.data.express_detail;
						if (!this.express_detail) {
							uni.showModal({
								title: '提示',
								content: '未查询相关物流信息',
								confirmText: '我知道了',
								showCancel: false
							})
							return;
						}
						if (this.express_detail.code == 0 && this.express_detail.data.list.length == 0) {
							uni.showModal({
								title: '提示',
								content: '未查询相关物流信息',
								confirmText: '我知道了',
								showCancel: false
							})
						}
					} else {
						uni.showModal({
							title: '提示',
							content: '未查询相关物流信息',
							confirmText: '我知道了',
							showCancel: false
						})
					}
				})
			},
			orderCancel(e) {
				let self = this;
				uni.showModal({
					title: '提示',
					content: '确认取消订单',
					success: function(e) {
						if (e.confirm) {
							self.$request({
								url: '/api/order/cancel',
								data: {
									order_id: self.order_id
								},
								method: 'post'
							}).then(res => {
								if (res.code == 0) {
									uni.showModal({
										title: '提示',
										content: res.msg,
										success: function(e) {
											uni.navigateBack({
												delta: 1
											})
										}
									})
								} else {
									uni.showModal({
										title: '提示',
										content: res.msg,
										success: function(e) {

										}
									})
								}
							})
						}
					}
				})

			},
			refund(row) {
				console.log(row);
				uni.navigateTo({
					url: "/pages/refund/refund?od_id=" + row.id + '&order_id=' + this.order_id
				})

			},
			getDetail() {
				this.$request({
					url: '/api/order/detail',
					data: {
						order_id: this.order_id
					}
				}).then(res => {
					if (res.code == 0) {
						this.order = res.data.order;
						this.list = res.data.list;
						this.common_order = res.data.common_order;
					} else {
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: function() {
								uni.navigateBack({
									delta: 1
								})
							}
						})
					}
					console.log(res);
				})
			},
			getImg: function(status) {
				let path = "/static/order/"
				let arr = ["img_order_payment3x.png", "img_order_send3x.png", "img_order_received3x.png",
					"img_order_signed3x.png", "img_order_closed3x.png"
				];
				if (this.order.is_cancel == 1) {
					return path + arr[4];
				}

				if (this.order.is_pay == 0) {
					return path + arr[0];
				}
				if (this.order.is_pay == 1 && this.order.is_send == 0) {
					return path + arr[1];
				}
				if (this.order.is_pay == 1 && this.order.is_send == 1 && this.order.is_confirm == 0) {
					return path + arr[2];
				}
				if (this.order.is_pay == 1 && this.order.is_send == 1 && this.order.is_confirm == 1) {
					return path + arr[3];
				}
			},
			getStatusText: function(status) {
				let order = this.order;
				if (this.order.is_cancel == 1) {
					return "交易关闭";
				}
				if (order.is_pay == 0) {
					return "等待您付款";
				}
				if (order.is_pay == 1 && order.is_send == 0) {
					return "等待卖家发货";
				}
				if (order.is_pay == 1 && order.is_send == 1 && order.is_confirm == 0) {
					return "待收货";
				}
				if (order.is_pay == 1 && order.is_send == 1 && order.is_confirm == 1 && order.status == 0) {
					return "待评价";
				}
				if (order.status == 1) {
					return "订单已完成";
				}
			},
			getReason: function(status) {
				return ["剩余时间", "等待卖家发货", "还剩X天XX小时自动确认", "", "超时未付款，订单自动取消"][status - 1]
			},
			switchStatus() {
				let status = this.status + 1
				this.status = status > 5 ? 1 : status
				this.tui.toast("状态切换成功")
			},
			logistics() {
				this.tui.href("/pages/extend/timeaxis/timeaxis?page=mall")
			},
			btnPay() {
				uni.navigateTo({
					url: '/pages/pay/pay?common_order_id=' + this.common_order.id
				})
			},
			confirm() {
				this.$http.loading();
				this.$request({
					url: '/api/order/confirm',
					data: {
						order_id: this.order.id
					},
					method: 'post'
				}).then(res => {
					this.$http.hideLoading();
					if (res.code == 0) {
						order.status = 3;
					}

				})

			},
			orderDelete() {
				let self = this;
				uni.showModal({
					title: '提示',
					content: '确认删除订单',
					success: function(e) {
						if (e.confirm) {
							self.$request({
								url: '/api/order/delete',
								data: {
									order_id: self.order_id
								},
								method: 'post'
							}).then(res => {
								if (res.code == 0) {
									uni.showModal({
										title: '提示',
										content: res.msg,
										success: function(e) {
											uni.navigateBack({
												delta: 1
											})
										}
									})
								} else {
									uni.showModal({
										title: '提示',
										content: res.msg,
										success: function(e) {

										}
									})
								}



							})

						}
					}
				})
			},
			popupClose() {
				this.show = false
			}
		}
	}
</script>

<style>
	.container {
		padding-bottom: 118rpx;
	}

	.tui-order-header {
		width: 100%;
		height: 160rpx;
		position: relative;
		background-color: #EB0909;
	}

	.tui-img-bg {
		width: 100%;
		height: 160rpx;
	}

	.tui-header-content {
		width: 100%;
		height: 160rpx;
		position: absolute;
		z-index: 10;
		left: 0;
		top: 0;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 70rpx;
		box-sizing: border-box;
	}

	.tui-status-text {
		font-size: 34rpx;
		line-height: 34rpx;
		color: #FEFEFE;
	}

	.tui-reason {
		font-size: 24rpx;
		line-height: 24rpx;
		color: rgba(254, 254, 254, 0.75);
		padding-top: 15rpx;
		display: flex;
		align-items: center;
	}

	.tui-reason-text {
		padding-right: 12rpx;
	}

	.tui-status-img {
		width: 80rpx;
		height: 80rpx;
		display: block;
	}

	.tui-flex-box {
		width: 100%;
		display: flex;
		align-items: center;
	}

	.tui-icon-img {
		width: 44rpx;
		height: 44rpx;
		flex-shrink: 0;
	}

	.tui-logistics {
		display: flex;
		flex-direction: column;
		justify-content: center;
		padding: 0 24rpx 0 20rpx;
		box-sizing: border-box;
	}

	.tui-logistics-text {
		font-size: 26rpx;
		line-height: 32rpx;
	}

	.tui-logistics-time {
		font-size: 24rpx;
		line-height: 24rpx;
		padding-top: 16rpx;
		color: #666
	}

	.tui-addr {
		display: flex;
		flex-direction: column;
		justify-content: center;
		padding-left: 20rpx;
		box-sizing: border-box;
	}

	.tui-addr-userinfo {
		font-size: 30rpx;
		line-height: 30rpx;
		font-weight: bold;
	}

	.tui-addr-text {
		font-size: 24rpx;
		line-height: 32rpx;
		padding-top: 16rpx;
	}

	.tui-addr-tel {
		padding-left: 40rpx;
	}

	.tui-order-item {
		margin-top: 20rpx;
		border-radius: 10rpx;
		overflow: hidden;
	}

	.tui-goods-title {
		width: 100%;
		font-size: 28rpx;
		line-height: 28rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}


	.tui-goods-item {
		width: 100%;
		padding: 20rpx 30rpx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
	}

	.tui-goods-img {
		width: 180rpx;
		height: 180rpx;
		display: block;
		flex-shrink: 0;
	}

	.tui-goods-center {
		flex: 1;
		padding: 20rpx 8rpx;
		box-sizing: border-box;
	}

	.tui-goods-name {
		max-width: 310rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
		font-size: 26rpx;
		line-height: 32rpx;
	}

	.tui-goods-attr {
		font-size: 22rpx;
		color: #888888;
		line-height: 32rpx;
		padding-top: 20rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}

	.tui-price-right {
		text-align: right;
		font-size: 24rpx;
		color: #888888;
		line-height: 30rpx;
		padding-top: 20rpx;
	}

	.tui-color-red {
		color: #E41F19;
		padding-right: 30rpx;
	}

	.tui-goods-price {
		width: 100%;
		display: flex;
		align-items: flex-end;
		justify-content: flex-end;
		font-size: 24rpx;
	}

	.tui-size-24 {
		font-size: 24rpx;
		line-height: 24rpx;
	}

	.tui-price-large {
		font-size: 32rpx;
		line-height: 30rpx;
	}

	.tui-goods-info {
		width: 100%;
		padding: 30rpx;
		box-sizing: border-box;
		background: #fff;
	}

	.tui-price-flex {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.tui-size24 {
		padding-bottom: 20rpx;
		font-size: 24rpx;
		line-height: 24rpx;
		color: #888;
	}

	.tui-size32 {
		font-size: 32rpx;
		line-height: 32rpx;
		font-weight: 500;
	}

	.tui-pbtm20 {
		padding-bottom: 20rpx;
	}

	.tui-flex-shrink {
		flex-shrink: 0;
	}

	.tui-primary-color {
		color: #EB0909;
	}

	.tui-order-info {
		margin-top: 20rpx;
	}

	.tui-order-title {
		position: relative;
		font-size: 28rpx;
		line-height: 28rpx;
		padding-left: 12rpx;
		box-sizing: border-box;
	}

	.tui-order-title::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		border-left: 4rpx solid #EB0909;
		height: 100%;
	}

	.tui-order-content {
		width: 100%;
		padding: 24rpx 30rpx;
		box-sizing: border-box;
		background: #fff;
		font-size: 24rpx;
		line-height: 30rpx;
	}

	.tui-order-flex {
		display: flex;
		padding-top: 18rpx;
	}

	.tui-order-flex:first-child {
		padding-top: 0
	}

	.tui-item-title {
		width: 132rpx;
		flex-shrink: 0;
	}

	.tui-item-content {
		color: #666;
		line-height: 32rpx;
	}

	.tui-safe-area {
		height: 1rpx;
		padding-bottom: env(safe-area-inset-bottom);
	}

	.tui-tabbar {
		width: 100%;
		height: 98rpx;
		background: #fff;
		position: fixed;
		left: 0;
		bottom: 0;
		display: flex;
		align-items: center;
		justify-content: flex-end;
		font-size: 26rpx;
		box-shadow: 0 0 1px rgba(0, 0, 0, .3);
		padding-bottom: env(safe-area-inset-bottom);
		z-index: 996;
	}

	.tui-btn-mr {
		margin-right: 30rpx;
	}
</style>
