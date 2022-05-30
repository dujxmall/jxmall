<template>
	<view class="container">
		<image :src="icons.banner" mode="widthFix" class="tui-coupon-banner"></image>

		<tui-tabs :tabs="navbar" selectedColor="#EB0909" sliderBgColor="#EB0909" :currentTab="currentTab" @change="tabChange"
		 itemWidth="50%"></tui-tabs>

		<view class="tui-coupon-list">
			<view class="tui-coupon-item tui-top20" v-for="(item,index) in list" :key="index">
				<image :src="icons.failure" class="tui-coupon-sign" v-if="item.status==2"></image>
				<image :src="icons.used" class="tui-coupon-sign" v-if="item.status==1"></image>
				<image :src="icons.bg" class="tui-coupon-bg" mode="widthFix"></image>
				<view class="tui-coupon-item-left">
					<view class="tui-coupon-price-box" :class="{'tui-color-grey':item.status!=0}">
						<view class="tui-coupon-price-sign" v-if="item.type==0">￥</view>
						<!--tui-price-small 面值>4位数的时候为true-->
						<view class="tui-coupon-price" :class="{'tui-price-small':false}">{{item.discount}}</view>
						<view class="tui-coupon-price-sign" v-if="item.type==1">折</view>
					</view>
					<view class="tui-coupon-intro">满{{item.price}}元可用</view>
				</view>
				<view class="tui-coupon-item-right">
					<view class="tui-coupon-content">
						<view class="tui-coupon-title-box">
							<view class="tui-coupon-btn" :class="{'tui-bg-grey':item.is_goods_limit==1?true:false}">全场券</view>
							<view class="tui-coupon-title"> {{item.is_goods_limit==0?'全部商品可用':'特定商品使用'}}</view>
						</view>
						<view class="tui-coupon-rule">
							<view class="tui-rule-box tui-padding-btm">
								<view class="tui-coupon-circle"></view>
								<view class="tui-coupon-text">不可叠加使用</view>
							</view>
							<view class="tui-rule-box">
								<view class="tui-coupon-circle"></view>
								<view class="tui-coupon-text">{{item.date}}</view>
							</view>
						</view>
					</view>
				</view>
				<view class="tui-btn-box" v-if="item.status==0&&currentTab==0">
					<tui-button type="danger" width="152rpx" height="52rpx" :size="24" shape="circle" @click="receive(item.id)">立即领取</tui-button>

				</view>
				<view class="tui-btn-box" v-if="item.status==0&&currentTab==1">
					<tui-button type="danger" width="152rpx" height="52rpx" :size="24" shape="circle" :plain="true" @click="goUse">去使用</tui-button>

				</view>
			</view>

		</view>

		<!--加载loadding-->
		<tui-loadmore v-if="loadding" :index="3" type="red"></tui-loadmore>
		<tui-nomore v-if="is_no_more" backgroundColor="#f5f5f5"></tui-nomore>
		<!--加载loadding-->
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentTab: 0,
				backgroundColor: "linear-gradient(90deg, rgb(255, 118, 38), rgb(252, 30, 82))",
				navbar: [{
					name: "领取"
				}, {
					name: "已领"
				}],
				loadding: false,
				pullUpOn: true,
				couponList: [1, 2, 3, 4, 5],
				gid: '',
				page: 1,
				list: [],
				is_no_more: false,
				icons: {},
			}
		},

		onLoad: function(e) {
			this.getSetting();
			if (e.gid) {
				this.gid = e.gid;
			}
			this.getList();
		},

		methods: {
			goUse() {
				uni.navigateBack({
					delta: 1
				})
			},
			getSetting() {
				this.$request({
					url: "/api/coupon/coupon-center",

				}).then(res => {
					if (res.code == 0) {
						this.icons = res.data.setting
					}

				})
			},

			tabChange(e) {
				this.currentTab = e.index
				this.page = 1;
				this.is_no_more = false;
				this.list = [];
				if (this.currentTab == 0) {
					this.getList();
				} else {
					this.getUserCouponList();
				}
			},
			receive(id, index) {
				this.$request({
					url: '/api/coupon/coupon-receive',
					data: {
						coupon_id: id
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						uni.showToast({
							title: '领取成功'
						})
					}
				})
			},
			getUserCouponList() {
				uni.showLoading({
					title: '加载中...'
				})
				this.$request({
					url: "/api/coupon/user-coupon-list",
					data: {
						page: this.page,
					}
				}).then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						if (res.data.pagination.page_count > this.page) {
							this.page++;
						} else {
							this.is_no_more = true;
						}
						if (this.page == 1) {
							this.list = res.data.list;
						} else {
							this.list = this.list.concat(res.data.list)
						}
					}

				})
			},

			getList() {
				if (this.is_no_more) {


					return;
				}
				uni.showLoading({
					title: '加载中...'
				})



				this.$request({
					url: "/api/coupon/coupon-list",
					data: {
						page: this.page,
						gid: this.gid
					}
				}).then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						if (res.data.pagination.page_count > this.page) {
							this.page++;
						} else {
							this.is_no_more = true;
						}
						if (this.page == 1) {
							this.list = res.data.list;
						} else {
							this.list = this.list.concat(res.data.list)
						}
					}

				})
			},


		},
		onPullDownRefresh() {
			this.page = 1;
			this.list = [];
			this.is_no_more = false;
			this.getList();

		},
		onReachBottom() {
			//只是测试效果，逻辑以实际数据为准
			this.getList();
		}
	}
</script>

<style>
	page {
		background-color: #F5F5F5;
	}

	.container {
		padding-bottom: env(safe-area-inset-bottom);
	}

	.tui-coupon-list {
		width: 100%;
		padding: 0 25rpx;
		box-sizing: border-box;
	}

	.tui-coupon-banner {
		width: 100%;
	}

	.tui-coupon-item {
		width: 100%;
		height: 210rpx;
		position: relative;
		display: flex;
		align-items: center;
		padding-right: 30rpx;
		box-sizing: border-box;
		overflow: hidden;
	}

	.tui-coupon-bg {
		width: 100%;
		height: 210rpx;
		position: absolute;
		left: 0;
		top: 0;
		z-index: 1;
	}

	.tui-coupon-sign {
		height: 110rpx;
		width: 110rpx;
		position: absolute;
		z-index: 9;
		top: -30rpx;
		right: 40rpx;
	}

	.tui-coupon-item-left {
		width: 218rpx;
		height: 210rpx;
		position: relative;
		z-index: 2;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		flex-shrink: 0;
	}

	.tui-coupon-price-box {
		display: flex;
		color: #e41f19;
		align-items: flex-end;
	}

	.tui-coupon-price-sign {
		font-size: 30rpx;
	}

	.tui-coupon-price {
		font-size: 70rpx;
		line-height: 68rpx;
		font-weight: bold;
	}

	.tui-price-small {
		font-size: 58rpx !important;
		line-height: 56rpx !important;
	}


	.tui-coupon-intro {
		background: #F7F7F7;
		padding: 8rpx 10rpx;
		font-size: 26rpx;
		line-height: 26rpx;
		font-weight: 400;
		color: #666;
		margin-top: 18rpx;
	}

	.tui-coupon-item-right {
		flex: 1;
		height: 210rpx;
		position: relative;
		z-index: 2;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding-left: 24rpx;
		box-sizing: border-box;
		overflow: hidden;
	}

	.tui-coupon-content {
		width: 82%;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}

	.tui-coupon-title-box {
		display: flex;
		align-items: center;
	}

	.tui-coupon-btn {
		padding: 6rpx;
		background: #FFEBEB;
		color: #e41f19;
		font-size: 25rpx;
		line-height: 25rpx;
		display: flex;
		align-items: center;
		justify-content: center;
		transform: scale(0.9);
		transform-origin: 0 center;
		border-radius: 4rpx;
		flex-shrink: 0;
	}

	.tui-color-grey {
		color: #888 !important;
	}

	.tui-bg-grey {
		background: #F0F0F0 !important;
		color: #888 !important;
	}

	.tui-coupon-title {
		width: 100%;
		font-size: 26rpx;
		color: #333;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.tui-coupon-rule {
		padding-top: 52rpx;
	}

	.tui-rule-box {
		display: flex;
		align-items: center;
		transform: scale(0.8);
		transform-origin: 0 100%;
	}

	.tui-padding-btm {
		padding-bottom: 6rpx;
	}

	.tui-coupon-circle {
		width: 8rpx;
		height: 8rpx;
		background: rgb(160, 160, 160);
		border-radius: 50%;
	}

	.tui-coupon-text {
		font-size: 28rpx;
		line-height: 28rpx;
		font-weight: 400;
		color: #666;
		padding-left: 8rpx;
		white-space: nowrap;
	}

	.tui-top20 {
		margin-top: 20rpx;
	}

	.tui-coupon-title {
		font-size: 28rpx;
		line-height: 28rpx;
	}


	.tui-coupon-radio {
		transform: scale(0.7);
		transform-origin: 100% center;
	}

	.tui-btn-box {
		position: absolute;
		width: 146rpx;
		height: 52rpx;
		right: 20rpx;
		bottom: 66rpx;
		z-index: 10;
	}

	/* #ifdef APP-PLUS || MP */
	.wx-radio-input {
		margin-right: 0 !important;
	}

	/* #endif */

	/* #ifdef H5 */
	>>>uni-radio .uni-radio-input {
		margin-right: 0 !important;
	}

	/* #endif */
</style>
