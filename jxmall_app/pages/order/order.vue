<template>
	<view class="container">
		<tui-tabs :tabs="tabs" :isFixed="scrollTop>=0" :currentTab="currentTab" selectedColor="#E41F19"
			sliderBgColor="#E41F19" @change="change" itemWidth="20%"></tui-tabs>
		<!--选项卡逻辑自己实现即可，此处未做处理-->
		<view :class="{'tui-order-list':scrollTop>=0}">
			<block v-if="list.length==0&&!loading">
				<tui-no-data :fixed="false" imgUrl="/static/images/toast/img_nodata.png" btnText="去逛逛" @click="extend">
					您还没有购买任何商品~</tui-no-data>
			</block>
			<view class="tui-order-item" v-if="list.length" v-for="(order,index) in list" :key="index">
				<tui-list-cell :hover="false" :lineLeft="false" style="font-size: 9pt" @click="detail(order.id)">
					<view class="tui-goods-title" style="font-size: 9pt">
						<view style="font-size: 9pt;">订单号：{{order.order_no}}</view>
						<view class="tui-order-status">
							<block v-if="order.status==0">待支付</block>
							<block v-if="order.status==1">待发货</block>
							<block v-if="order.status==2">待收货</block>
							<block v-if="order.status==3">待评价</block>
							<block v-if="order.status==4">已完成</block>
							<block v-if="order.status==5">已关闭</block>
						</view>
					</view>
				</tui-list-cell>
				<block v-for="(item,i) in order.detail_list" :key="i">
					<tui-list-cell padding="0" @click="detail(order.id)">
						<view class="tui-goods-item">
							<image :src="item.goods.cover_pic" class="tui-goods-img"></image>
							<view class="tui-goods-center">
								<view class="tui-goods-name">{{item.goods.name}}</view>
								<view class="tui-goods-attr" v-if="item.attr.length" v-for="attr in item.attr">
									{{attr.attr_group_name}}
									{{attr.attr_name}}
								</view>
							</view>
							<view class="tui-price-right">
								<view>￥{{item.price}}</view>
								<view>x{{item.num}}</view>
							</view>
						</view>
					</tui-list-cell>
				</block>
				<tui-list-cell :hover="false" unlined @click="detail(order.id)">
					<view class="tui-goods-price flex-x-bottom">
						<view> 实付：</view>
						<view class="tui-size-24">￥</view>
						<view class="tui-price-large">{{order.pay_price}}</view>
					</view>
				</tui-list-cell>
				<view class="tui-order-btn">
					<view class="tui-btn-ml" v-if="order.status==0">
						<tui-button type="danger" plain width="152rpx" height="56rpx" :size="26" shape="circle"
							@click="pay(order)">立即支付</tui-button>
					</view>
					<view class="tui-btn-ml" v-if="order.status==3">
						<tui-button type="black" plain width="152rpx" height="56rpx" :size="26" shape="circle"
							@click="comment(order)">评价晒单</tui-button>
					</view>
					<view class="tui-btn-ml" v-if="order.status==2">
						<tui-button type="danger" plain width="152rpx" height="56rpx" :size="26" @click="confirm(order)"
							shape="circle">确认收货</tui-button>
					</view>
					<block v-if="$util.getSetting('is_ban_cancel')!=1">
						<view class="tui-btn-ml" v-if="order.status==0||order.status==1">
							<tui-button type="danger" plain width="152rpx" height="56rpx" :size="26"
								@click="cancel(order)" shape="circle">取消</tui-button>
						</view>
					</block>

				</view>
			</view>
		</view>
		<!--加载loading-->
		<tui-loadmore v-if="loading" :index="3" type="red"></tui-loadmore>
		<tui-nomore v-if="!pullUpOn" backgroundColor="#fafafa"></tui-nomore>
		<!--加载loading-->

	</view>
</template>

<script>
	export default {
		data() {
			return {
				tabs: [{
						name: "全部"
					}, {
						name: "待付款"
					}, {
						name: "待发货"
					}, {
						name: "待收货"
					}, {
						name: "待评价"
					},
					{
						name: "已完成"
					},
					{
						name: "已关闭"
					}
				],
				currentTab: 0,
				pageIndex: 1,
				loading: false,
				pullUpOn: true,
				scrollTop: 0,
				list: [],
				status: -1,
				page: 1,
				is_no_more: false,
				setting: null,
			}
		},
		onLoad: function(options) {		 
			if (options.status) {
				this.status = options.status;
				this.currentTab = parseInt(this.status) + 1;
			}
			this.getList();
		},
		methods: {

			extend() {
				uni.navigateBack()
			},
			comment(order) {
				uni.navigateTo({
					url: '/pages/comment/comment?order_id=' + order.id
				})
			},
			detail(id) {
				uni.navigateTo({
					url: "/pages/order-detail/order-detail?order_id=" + id
				})
			},
			change(e) {
				this.currentTab = e.index
				this.status = this.currentTab - 1;
				this.list = [];
				this.page = 1;
				this.is_no_more = false;

				this.getList();
			},
			getList() {
				if (this.is_no_more) {
					uni.showToast({
						title: '没有更多数据'
					})
					return;
				}
				this.loading = true
				this.$request({
					url: '/api/order/list',
					data: {
						status: this.status,
						page: this.page
					}
				}).then(res => {
					this.loading = false;
					if (res.code == 0) {
						if (this.page == 1) {
							this.list = res.data.list
						} else {
							this.list = this.list.concat(res.data.list);
						}
						if (res.data.pagination.page_count > this.page) {
							this.page++;
						} else {
							this.is_no_more = true;
						}
					}
				})
			},
			confirm(order) {
				this.$http.loading();
				this.$request({
					url: '/api/order/confirm',
					data: {
						order_id: order.id
					},
					method: 'post'
				}).then(res => {
					this.$http.hideLoading();
					if (res.code == 0) {
						order.status = 3;
					}

				})

			},
			pay(order) {

				if (order.common_order_id) {
					uni.navigateTo({
						url: "/pages/pay/pay?common_order_id=" + order.common_order_id
					})
				}

			},
			cancel(order) {

				this.$http.loading();
				this.$request({
					url: '/api/order/cancel',
					data: {
						order_id: order.id
					},
					method: 'post'
				}).then(res => {
					this.$http.hideLoading();
					if (res.code == 0) {
						order.status = 5;
					} else {
						uni.showModal({
							title: '提示',
							content: res.msg
						})
					}

				})
			},
		},

		onPullDownRefresh() {
			this.page = 0;
			this.list = [];
			this.pullUpOn = true
			this.getList();
		},
		onReachBottom() {
			//只是测试效果，逻辑以实际数据为准
			this.pullUpOn = true
			this.getList();
		},
		onPageScroll(e) {
			this.scrollTop = e.scrollTop;
		}
	}
</script>

<style>
	.container {
		padding-bottom: env(safe-area-inset-bottom);
	}

	.tui-order-list {
		margin-top: 80rpx;
	}

	.tui-order-item {
		margin-top: 20rpx;
		border-radius: 10rpx;
		overflow: hidden;
	}

	.tui-goods-title {
		width: 100%;
		font-size: 28rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.tui-order-status {
		color: #888;
		font-size: 26rpx;
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
		font-weight: 500;
	}

	.tui-order-btn {
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: flex-end;
		background: #fff;
		padding: 10rpx 30rpx 20rpx;
		box-sizing: border-box;
	}

	.tui-btn-ml {
		margin-left: 20rpx;
	}
</style>
