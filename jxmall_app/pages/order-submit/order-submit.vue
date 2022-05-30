<template>

	<tui-page>
		<view class="container">
			<view class="tui-box">
				<tui-list-cell :arrow="true" unlined :radius="true" @click="chooseAddr">
					<block v-if="address">
						<view class="tui-address">
							<view v-if="true">
								<view class="tui-userinfo">
									<text class="tui-name">{{address.name}}</text> <text> {{address.mobile}}</text>
								</view>
								<view class="tui-addr">
									<text>{{address.address}}</text>
								</view>
							</view>
							<view class="tui-none-addr" v-else>
								<image src="/static/images/index/map.png" class="tui-addr-img" mode="widthFix"></image>
								<text>选择收货地址</text>
							</view>
						</view>
						<view class="tui-bg-img"></view>
					</block>
					<block v-if="!address">
						<view class="tui-address">
							<view v-if="true">
								<view class="tui-userinfo">
									<text class="tui-name">请选择收货地址</text>
								</view>
							</view>
							<view class="tui-none-addr" v-else>
								<image src="/static/images/index/map.png" class="tui-addr-img" mode="widthFix"></image>
								<text>选择收货地址</text>
							</view>
						</view>
						<view class="tui-bg-img"></view>

					</block>
				</tui-list-cell>
				<view class="tui-top tui-goods-info">
					<tui-list-cell :hover="false" :lineLeft="false">
						<view class="tui-goods-title">
							商品信息
						</view>
					</tui-list-cell>
					<block v-if="mch_list.length>0" v-for="(mch,i) in mch_list">
						<block v-for="(item,index) in mch.goods_list" :key="index">
							<tui-list-cell :hover="false" padding="0">
								<view class="tui-goods-item">
									<image :src="item.cover_pic" class="tui-goods-img"></image>
									<view class="tui-goods-center">
										<view class="tui-goods-name">{{item.name}}</view>
										<view class="tui-goods-attr" v-if="item.goods_attr_id==0">规格：默认</view>
										<view class="tui-goods-attr" v-if="item.goods_attr_id"
											v-for="attr in item.attr_list">{{attr.attr_group_name}}：{{attr.attr_name}}
										</view>
									</view>
									<view class="tui-price-right">
										<view>￥{{item.price}}</view>
										<view>x{{item.num}}</view>
									</view>
								</view>
							</tui-list-cell>
						</block>
						<tui-list-cell :hover="false">
							<view class="tui-padding tui-flex">
								<view>商品总额</view>
								<view>￥{{mch.origin_total_goods_price}}</view>
							</view>
						</tui-list-cell>
						<tui-list-cell :hover="false" v-if="express_price>0">
							<view class="tui-padding tui-flex">
								<view>运费</view>
								<view>￥{{express_price}}</view>
							</view>
						</tui-list-cell>
						<tui-list-cell :hover="false" :lineLeft="false" padding="0">
							<view class="tui-remark-box tui-padding tui-flex">
								<view>订单备注</view>
								<input type="text" class="tui-remark" v-model="mch.remarks" placeholder="选填: 请先和商家协商一致"
									placeholder-class="tui-phcolor"></input>
							</view>
						</tui-list-cell>

					</block>
					<tui-list-cell :hover="false" v-if="coupon_list.length>0">
						<view class="tui-padding tui-flex">
							<view>优惠券</view>
							<view @click="popupShow=!popupShow" class="flex-y-center">
								<text v-if="!user_coupon_id">选择优惠券</text>
								<text v-if="user_coupon_id" style="color: #EB0909;">-{{coupon_cut_price}} 元 </text>
								<tui-icon :size="20" :name="'arrowright'"></tui-icon>
							</view>
						</view>
					</tui-list-cell>
					<tui-list-cell :hover="false" v-if="integral_cut_price>0">
						<view class="tui-padding tui-flex">
							<view>可以使用 <text style="color:#EB0909;padding: 0 5rpx;">{{total_use_integral}}积分
								</text>抵扣<text style="color:#EB0909;padding: 0 5rpx;">
									{{integral_cut_price}} 元</text></view>
							<view class="flex-y-center">
								<switch style="transform:scale(0.7)" :checked="is_use_integral"
									@change="useIntgralChange" color="#EB0909" />
							</view>
						</view>
					</tui-list-cell>
					<tui-list-cell :hover="false" unlined>
						<view class="tui-padding tui-flex tui-total-flex">
							<view class="tui-flex-end tui-color-red">
								<view class="tui-black">合计： </view>
								<view class="tui-size-26">￥</view>
								<view class="tui-price-large">{{total_price}}</view>
							</view>
						</view>
					</tui-list-cell>
				</view>
			</view>
			<view class="tui-safe-area"></view>

			<view class="tui-tabbar">
				<view class="tui-flex-end tui-color-red tui-pr-20">
					<view class="tui-black">实付金额: </view>
					<view class="tui-size-26">￥</view>
					<view class="tui-price-large">{{total_price}}</view>
				</view>
				<view class="tui-pr25">
					<tui-button :width="'200rpx'" height="70rpx" :size="28" type="danger" shape="circle"
						@click="submit">确认提交</tui-button>
				</view>
			</view>

		</view>
		<tui-bottom-popup :show="popupShow" @close="hidePopup">
			<scroll-view scroll-y class="tui-popup-scroll" v-if="coupon_list.length">
				<view class="tui-scrollview-box">
					<tui-coupon-pop @selected="couponSelected" :list="coupon_list"></tui-coupon-pop>
				</view>
			</scroll-view>
			<view class="tui-right" style="z-index: 999999;">
				<tui-icon name="close-fill" color="#999" :size="20" @click="hidePopup"></tui-icon>
			</view>
		</tui-bottom-popup>
	</tui-page>
</template>

<script>
	export default {

		data() {
			return {

				popupShow: false,
				hasCoupon: true,
				insufficient: false,
				show: false,
				order_submit_data: null,
				mch_list: [],
				goods_total_price: '0.00',
				total_price: '0.00',
				address: null,
				express_price: '0.00',
				coupon_list: [],
				is_use_integral: false,
				user_coupon_id: '',
				use_coupon_goods_id: '',
				coupon_cut_price: '',
				integral_cut_price: 0,
				total_use_integral: 0,

			}
		},
		onShow: function() {
			let address = uni.getStorageSync('SELECT_ADDRESS');
			if (address) {
				this.address = address;
				this.getOrderData();
			}

		},
		onLoad: function(e) {
			this.order_submit_data = uni.getStorageSync('ORDER_SUBMIT_DATA');
			if (!this.order_submit_data) {
				uni.showModal({
					title: '提示',
					content: '没有订单数据',
					success: function(e) {
						uni.navigateBack({
							delta: 1
						})
					}
				})
				return;
			}
			this.getOrderData();
		},
		methods: {
			useIntgralChange(e) {
				this.is_use_integral = !this.is_use_integral
				this.getOrderData();
			},
			couponSelected(e) {
				this.hidePopup();
				this.user_coupon_id = e.user_coupon_id;
				this.use_coupon_goods_id = e.use_coupon_goods_id;
				this.getOrderData();
			},
			hidePopup(e) {
				this.popupShow = false;
			},
			getOrderData() {
				uni.showLoading({
					title: '正在结算...'
				})
				this.$request({
					url: '/api/order/order-preview',
					data: {
						order_data: JSON.stringify(this.order_submit_data),
						address_id: this.address ? this.address.id : 0,
						user_coupon_id: this.user_coupon_id,
						use_coupon_goods_id: this.use_coupon_goods_id,
						is_use_integral: this.is_use_integral ? 1 : 0
					},
					method: 'post'
				}).then(res => {
					uni.hideLoading();
					if (res.code === 0) {
						this.total_use_integral = res.data.total_use_integral;
						this.integral_cut_price = res.data.integral_cut_price;
						if (this.coupon_list.length == 0) {
							this.coupon_list = res.data.coupon_list;
						}
						if (res.data.coupon_cut_price) {
							this.coupon_cut_price = res.data.coupon_cut_price;
						}
						this.mch_list = res.data.mch_list;
						this.goods_total_price = res.data.goods_total_price;

						/*  */
						if (!this.address) {
							this.address = res.data.default_address;
						}
						this.express_price = res.data.express_price;
						this.total_price = res.data.total_price;
					} else {
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
			chooseAddr() {
				uni.navigateTo({
					url: "../address/address?type=SELECT"
				})
			},
			submit() {
				if (!this.address) {
					this.$showModal('请选择地址');
					return;
				}
				this.$request({
					url: '/api/order/submit',
					data: {
						mch_list: JSON.stringify(this.mch_list),
						address: this.address.address,
						address_id: this.address.id
					},
					method: 'post'
				}).then(res => {
					uni.removeStorageSync('SELECT_ADDRESS');
					if (res.code == 0) {
						if (res.data.is_union_order == 1) {
							uni.redirectTo({
								url: '/pages/pay/pay?union_order_id=' + res.data.union_order_id
							})
						}
					} else {
						this.$showModal(res.msg).then(res => {
							console.log(res);
						})
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
		padding-bottom: 98rpx;
	}




	.tui-right {
		position: absolute;
		right: 30rpx;
		top: 30rpx;
	}

	.tui-box {
		padding: 20rpx 0 118rpx;
		box-sizing: border-box;
	}

	.tui-address {
		min-height: 80rpx;
		padding: 10rpx 0;
		box-sizing: border-box;
		position: relative;
	}

	.tui-userinfo {
		font-size: 30rpx;
		font-weight: 500;
		line-height: 30rpx;
		padding-bottom: 12rpx;
	}

	.tui-name {
		padding-right: 40rpx;
	}

	.tui-addr {
		font-size: 24rpx;
		word-break: break-all;
		padding-right: 25rpx;
	}

	.tui-addr-tag {
		padding: 5rpx 8rpx;
		flex-shrink: 0;
		background: #EB0909;
		color: #fff;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 25rpx;
		line-height: 25rpx;
		transform: scale(0.8);
		transform-origin: 0 center;
		border-radius: 6rpx;
	}

	.tui-bg-img {
		position: absolute;
		width: 100%;
		height: 8rpx;
		left: 0;
		bottom: 0;
		background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAL0AAAAECAMAAADszM6/AAAAOVBMVEUAAAAVqfH/fp//vWH/vWEVqfH/fp8VqfH/fp//vWEVqfH/fp8VqfH/fp//vWH/vWEVqfH/fp//vWHpE7b6AAAAEHRSTlMA6urqqlVVFRUVq6upqVZUDT4vVAAAAEZJREFUKM/t0CcOACAQRFF6r3v/w6IQJGwyDsPT882IQzQE0E3chToByjG5LwMgLZN3TQATmdypCciBya0cgOT3/h//9PgF49kd+6lTSIIAAAAASUVORK5CYII=") repeat;
	}

	.tui-top {
		margin-top: 20rpx;
		overflow: hidden;
	}

	.tui-goods-title {
		font-size: 28rpx;
		display: flex;
		align-items: center;
	}

	.tui-padding {
		box-sizing: border-box;
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

	.tui-flex {
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: space-between;
		font-size: 26rpx;
	}

	.tui-total-flex {
		justify-content: flex-end;
	}

	.tui-color-red,
	.tui-invoice-text {
		color: #E41F19;
		padding-right: 30rpx;
	}

	.tui-balance {
		font-size: 28rpx;
		font-weight: 500;
	}

	.tui-black {
		color: #222;
		line-height: 30rpx;
	}

	.tui-gray {
		color: #888888;
		font-weight: 400;
	}

	.tui-light-dark {
		color: #666;
	}

	.tui-goods-price {
		display: flex;
		align-items: center;
		padding-top: 20rpx;
	}

	.tui-size-26 {
		font-size: 26rpx;
		line-height: 26rpx;
	}

	.tui-price-large {
		font-size: 34rpx;
		line-height: 32rpx;
		font-weight: 600;
	}

	.tui-flex-end {
		display: flex;
		align-items: flex-end;
		padding-right: 0;
	}

	.tui-phcolor {
		color: #B3B3B3;
		font-size: 26rpx;
	}

	/* #ifndef H5 */
	.tui-remark-box {
		padding: 22rpx 30rpx;
	}

	/* #endif */
	/* #ifdef H5 */
	.tui-remark-box {
		padding: 26rpx 30rpx;
	}

	/* #endif */

	.tui-remark {
		flex: 1;
		font-size: 26rpx;
		padding-left: 64rpx;
	}

	.tui-scale-small {
		transform: scale(0.8);
		transform-origin: 100% center;
	}

	.tui-scale-small .wx-switch-input {
		margin: 0 !important;
	}

	/* #ifdef H5 */
	>>>uni-switch .uni-switch-input {
		margin-right: 0 !important;
	}

	/* #endif */
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

	.tui-pr-30 {
		padding-right: 30rpx;
	}

	.tui-pr-20 {
		padding-right: 20rpx;
	}

	.tui-none-addr {
		height: 80rpx;
		padding-left: 5rpx;
		display: flex;
		align-items: center;
	}

	.tui-addr-img {
		width: 36rpx;
		height: 46rpx;
		display: block;
		margin-right: 15rpx;
	}


	.tui-pr25 {
		padding-right: 25rpx;
	}

	.tui-safe-area {
		height: 1rpx;
		padding-bottom: env(safe-area-inset-bottom);
	}
</style>
