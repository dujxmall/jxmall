<template>
	<tui-page style="position: relative;">
		<view class="container" style="position: relative;">
			<!-- #ifdef MP || H5-->
			<view class="tui-edit-goods">
				<view>购物车共<text class="tui-goods-num">{{list.length}}</text>件商品</view>
				<view>
					<tui-button type="gray" :plain="true" shape="circle" width="160rpx" height="60rpx" :size="24"
						@click="editGoods">{{isEdit?"完成":"编辑商品"}}</tui-button>
				</view>
			</view>
			<!-- #endif -->

			<block v-if="list.length==0">
				<tui-no-data v-if="!loadding" :fixed="false" imgUrl="/static/images/toast/img_nodata.png">购物车空空如也~
				</tui-no-data>
			</block>
			<checkbox-group @change="buyChange">
				<block v-if="list.length">
					<view class="tui-cart-cell  tui-mtop" v-for="(item,index) in list" :key="index"
						v-if="item.is_valid==1">
						<tui-swipe-action :actions="actions" @click="handlerButton" :params="item">
							<template v-slot:content>
								<view class="tui-goods-item">
									<label class="tui-checkbox">
										<checkbox :value="item.id" :checked="item.selected" color="#fff"></checkbox>
									</label>
									<image :src="item.cover_pic" class="tui-goods-img" />
									<view class="tui-goods-info">
										<view class="tui-goods-title">
											{{item.name}}
										</view>
										<view class="tui-goods-model" v-if="item.is_attr" v-for="attr in item.attr">
											<view class="tui-model-text">{{attr.attr_group_name}} : {{attr.attr_name}}
											</view>
										</view>
										<view class="tui-goods-model" v-if="item.is_attr==0">
											<view class="tui-model-text">规格 : 默认</view>
										</view>
										<view class="tui-price-box">
											<view class="tui-goods-price">￥{{item.price}}</view>
											<tui-numberbox :value="item.num" :height="36" :width="64" :min="1"
												:index="index" @change="changeNum"></tui-numberbox>
										</view>
									</view>
								</view>
							</template>
						</tui-swipe-action>
					</view>
				</block>

			</checkbox-group>

			<!--商品失效-->
			<view class="tui-cart-cell  tui-mtop" style="display: none;">
				<view class="tui-activity">
					<view class="tui-bold">失效商品</view>
					<view class="tui-buy">
						<tui-button type="gray" :plain="true" shape="circle" width="200rpx" height="56rpx" :size="24">
							清空失效商品</tui-button>
					</view>
				</view>
			</view>

			<!--tabbar-->
			<view class="tui-tabbar" :style="{bottom:platform=='ios'?'169rpx':'100rpx'}">
				<view class="tui-checkAll">
					<checkbox-group @change="checkAll">
						<label class="tui-checkbox">
							<checkbox :checked="isAll" color="#fff"></checkbox>
							<text class="tui-checkbox-pl">全选</text>
						</label>
					</checkbox-group>
					<view class="tui-total-price" v-if="!isEdit">合计:<text
							class="tui-bold">￥{{totalPrice | getPrice}}</text> </view>
				</view>
				<view>
					<tui-button width="160rpx" height="60rpx" :size="25" type="danger" shape="circle" v-if="!isEdit"
						@click="btnPay">去结算({{buyNum}})</tui-button>
					<tui-button width="160rpx" height="60rpx" :size="25" type="danger" shape="circle" :plain="true"
						@click="deleteList" v-else>删除</tui-button>
				</view>
			</view>




			<!--加载loadding-->
			<tui-loadmore v-if="loadding" :index="3" type="red"></tui-loadmore>
		</view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				dataList: [{
					id: "t2020003120",
					buyNum: 2,
					price: 299.5,
					selected: false
				}, {
					id: 't1020003120',
					buyNum: 1,
					price: 499,
					selected: false
				}],
				isAll: false,
				totalPrice: 0,
				buyNum: 0,
				cartIds: [], //购物车id
				actions: [{
					name: '删除',
					color: '#fff',
					fontsize: 28,
					width: 64,
					background: '#F82400'
				}],
				actions2: [{
						name: '看相似',
						color: '#fff',
						fontsize: 28,
						width: 64,
						background: '#FF7035'
					},
					{
						name: '删除',
						color: '#fff',
						fontsize: 28,
						width: 64,
						background: '#F82400'
					}
				],
				isEdit: false,
				pageIndex: 1,
				loadding: false,
				pullUpOn: true,
				page: 1,
				list: [],
				is_no_more: false,
				platform: 'android'
			}
		},
		filters: {
			getPrice(price) {
				price = price || 0;
				return price.toFixed(2)
			}
		},
		onLoad: function(e) {


			this.platform = (uni.getSystemInfoSync().platform == 'android') ? 'android' : 'ios';
			
			console.log(this.platform);
			if (uni.getStorageSync('CART_GOODS_LIST')) {
				this.list = uni.getStorageSync('CART_GOODS_LIST')
			}
		},
		onShow: function() {

			this.getList()
		},
		methods: {
			deleteList() {
				let editList = [];
				this.list.map((item) => {
					if (item.selected) {
						editList.push({
							id: item.id,
							num: item.num
						})
					}
				})
				this.$request({
					url: '/api/cart/edit',
					data: {
						is_delete: 1,
						edit_data: JSON.stringify(editList)
					},
					method: 'post'
				}).then(res => {
					this.page = 1;
					this.list = [];
					this.is_no_more = false;
					this.getList();
				})
			},
			getList() {
				if (this.is_no_more) {
					uni.showToast({
						title: "没有更多数据"
					})
					return;
				}
				this.loadding = true;
				this.$request({
					url: '/api/cart/list',
					data: {
						page: this.page
					}
				}).then(res => {
					this.loadding = false;
					if (res.code == 0) {
						if (this.page == 1) {
							this.list = res.data.list;
						} else {
							this.list = this.list.concat(res.data.list)
						}
						if (res.data.page_count > this.page) {
							this.page++
						} else {
							this.is_no_more = true;
						}

						uni.setStorageSync('CART_GOODS_LIST', this.list)
					}
				})
			},

			calcHandle() {
				let buyNum = 0;
				let totalPrice = 0;
				let selectedNum = 0;
				this.list.map((item) => {
					if (item.selected) {
						buyNum += item.num;
						totalPrice += item.price * item.num;
						selectedNum++
					}
				})
				this.isAll = selectedNum === this.list.length
				this.buyNum = buyNum
				this.totalPrice = totalPrice
				console.log(this.totalPrice);
			},
			changeNum: function(e) {
				this.list[e.index].num = e.value
				setTimeout(() => {
					this.calcHandle()
				}, 0)
			},
			handlerButton: function(e) {
				let index = e.index;
				let item = e.item;
				console.log(item);
				this.$request({
					url: '/api/cart/delete',
					data: {
						id: item.id,
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.list.splice(index, 1)
					} else {
						uni.showToast({
							title: res.msg
						})
					}


				})


			},
			editGoods: function() {
				if (this.isEdit) {
					let editList = [];
					this.list.map((item) => {
						if (item.selected) {
							editList.push({
								id: item.id,
								num: item.num
							})
						}
					})
					this.$request({
						url: '/api/cart/edit',
						data: {
							is_delete: 0,
							edit_data: JSON.stringify(editList)
						},
						method: 'post'
					}).then(res => {
						console.log(res);
					})
				}
				this.isEdit = !this.isEdit;
			},
			detail: function() {
				uni.navigateTo({
					url: '../productDetail/productDetail'
				})
			},
			btnPay() {
				let selectList = [];
				this.list.map((item) => {
					if (item.selected) {
						let is_in = false;
						for (let i in selectList) {
							let mch = selectList[i];
							if (mch.mch_id == item.mch_id) {
								is_in = true;
								mch.goods_list.push({
									goods_id: item.goods_id,
									goods_attr_id: item.goods_attr_id,
									num: item.num
								})
								break;
							}
						}
						if (!is_in) {
							selectList.push({
								mch_id: item.mch_id,
								goods_list: [{
									goods_id: item.goods_id,
									goods_attr_id: item.goods_attr_id,
									num: item.num
								}],

							})
						}
					}
				})

				if (selectList.length === 0) {
					uni.showToast({
						title: '请选择商品'
					})
					return;
				}
				uni.setStorageSync('ORDER_SUBMIT_DATA', selectList);
				uni.navigateTo({
					url: '/pages/order-submit/order-submit'
				})
			},
			buyChange(e) {
				this.cartIds = e.detail.value;
				this.list.map(item => {
					//如果购物车id为数字统一转成字符串
					if (~this.cartIds.indexOf(item.id)) {
						item.selected = true;
					} else {
						item.selected = false;
					}
				})
				setTimeout(() => {
					this.calcHandle()
				}, 0)
			},
			checkAll(e) {
				this.isAll = !this.isAll;
				let buyNum = 0;
				let totalPrice = 0;
				this.list.map((item) => {
					item.selected = this.isAll;
					if (this.isAll) {
						buyNum += item.num;
						totalPrice += item.price * item.num;
					}
				})
				this.totalPrice = totalPrice;
				this.buyNum = buyNum;
			}
		},
		onReachBottom: function() {
			this.getList()
		},
		onNavigationBarButtonTap(e) {
			this.isEdit = !this.isEdit;
			let text = this.isEdit ? "完成" : "编辑";
			// #ifdef APP-PLUS
			let webView = this.$mp.page.$getAppWebview();
			webView.setTitleNViewButtonStyle(0, {
				text: text
			});
			// #endif
		}
	}
</script>

<style>
	.container {
		padding-bottom: 120rpx;
	}

	.tui-mtop {
		margin-top: 24rpx;
	}

	.tui-edit-goods {
		width: 100%;
		border-radius: 12rpx;
		overflow: hidden;
		padding: 24rpx 30rpx 0 30rpx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
		align-items: center;
		color: #333;
		font-size: 24rpx;
	}

	.tui-goods-num {
		font-weight: bold;
		color: #e41f19;
	}

	.tui-cart-cell {
		width: 100%;
		border-radius: 12rpx;
		background: #FFFFFF;
		padding: 40rpx 0;
		overflow: hidden;
	}

	.tui-goods-item {
		display: flex;
		padding: 0 30rpx;
		box-sizing: border-box;
	}

	.tui-checkbox {
		min-width: 70rpx;
		display: flex;
		align-items: center;
	}

	/* #ifdef MP-WEIXIN */
	.tui-checkbox .wx-checkbox-input {
		width: 40rpx;
		height: 40rpx;
		margin-right: 0 !important;
		border-radius: 50% !important;
		transform: scale(0.8);
		border-color: #d1d1d1 !important;
	}

	.tui-checkbox .wx-checkbox-input.wx-checkbox-input-checked {
		background: #eb0909;
		width: 44rpx !important;
		height: 44rpx !important;
		border: none;
	}

	/* #endif */
	/* #ifndef MP-WEIXIN */

	>>>.tui-checkbox .uni-checkbox-input {
		width: 40rpx;
		height: 40rpx;
		margin-right: 0 !important;
		border-radius: 50% !important;
		transform: scale(0.8);
		border-color: #d1d1d1 !important;
	}

	>>>.tui-checkbox .uni-checkbox-input.uni-checkbox-input-checked {
		background: #eb0909;
		width: 45rpx !important;
		height: 45rpx !important;
		border: none;
	}

	/* #endif */
	.tui-goods-img {
		width: 220rpx;
		height: 220rpx !important;
		border-radius: 12rpx;
		flex-shrink: 0;
		display: block;
	}

	.tui-goods-info {
		width: 100%;
		padding-left: 20rpx;
		display: flex;
		flex-direction: column;
		align-items: flex-start;
		justify-content: space-between;
		box-sizing: border-box;
		overflow: hidden;
	}

	.tui-goods-title {
		white-space: normal;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
		font-size: 24rpx;
		color: #333;
	}

	.tui-goods-model {
		max-width: 100%;
		color: #333;
		background: #F5F5F5;
		border-radius: 40rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 16rpx;
		box-sizing: border-box;
	}

	.tui-model-text {
		max-width: 100%;
		transform: scale(0.9);
		transform-origin: 0 center;
		font-size: 24rpx;
		line-height: 32rpx;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.tui-price-box {
		width: 100%;
		display: flex;
		align-items: flex-end;
		justify-content: space-between;
	}

	.tui-goods-price {
		font-size: 34rpx;
		font-weight: 500;
		color: #e41f19;
	}

	.tui-scale {
		transform: scale(0.8);
		transform-origin: 100% 100%;
	}

	.tui-activity {
		font-size: 24rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 30rpx 20rpx 100rpx;
		box-sizing: border-box;
	}

	.tui-buy {
		display: flex;
		align-items: center
	}

	.tui-bold {
		font-weight: bold;
	}

	.tui-sub-info {
		max-width: 532rpx;
		font-size: 24rpx;
		line-height: 24rpx;
		padding: 20rpx 30rpx 10rpx 30rpx;
		box-sizing: border-box;
		color: #333;
		transform: scale(0.8);
		transform-origin: 100% center;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		margin-left: auto
	}

	.tui-invalid-text {
		width: 66rpx;
		margin-right: 4rpx;
		text-align: center;
		font-size: 24rpx;
		color: #fff;
		background: rgba(0, 0, 0, .3);
		transform: scale(0.8);
		transform-origin: center center;
		border-radius: 4rpx;
		flex-shrink: 0;
	}

	.tui-gray {
		color: #B2B2B2 !important;
	}

	.tui-goods-invalid {
		color: #555;
		font-size: 24rpx;
	}

	.tui-flex-center {
		align-items: center !important;
	}

	.tui-invalid-ptop {
		padding-top: 40rpx;
	}

	.tui-tabbar {
		width: 100%;
		height: 120rpx;
		background: #fff;
		position: fixed;
		left: 0;
		bottom: 100rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 30rpx;
		box-sizing: border-box;
		font-size: 24rpx;
		z-index: 99999;
	}

	.tui-tabbar::before {
		content: '';
		width: 100%;
		border-top: 1rpx solid #d9d9d9;
		position: absolute;
		top: 0;
		left: 0;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
	}

	.tui-checkAll {
		display: flex;
		align-items: center;
	}

	.tui-checkbox-pl {
		padding-left: 12rpx;
	}

	.tui-total-price {
		padding-left: 30rpx;
		font-size: 30rpx !important;
	}

	/*猜你喜欢*/
	.tui-youlike {
		padding-left: 12rpx
	}

	.tui-product-list {
		display: flex;
		justify-content: space-between;
		flex-direction: row;
		flex-wrap: wrap;
		box-sizing: border-box;
	}

	.tui-product-container {
		flex: 1;
		margin-right: 2%;
	}

	.tui-product-container:last-child {
		margin-right: 0;
	}

	.tui-pro-item {
		width: 100%;
		margin-bottom: 4%;
		background: #fff;
		box-sizing: border-box;
		border-radius: 12rpx;
		overflow: hidden;
	}

	.tui-pro-img {
		width: 100%;
		display: block;
	}

	.tui-pro-content {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		box-sizing: border-box;
		padding: 20rpx;
	}

	.tui-pro-tit {
		color: #2e2e2e;
		font-size: 26rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}

	.tui-pro-price {
		padding-top: 18rpx;
	}

	.tui-sale-price {
		font-size: 34rpx;
		font-weight: 500;
		color: #e41f19;
	}

	.tui-factory-price {
		font-size: 24rpx;
		color: #a0a0a0;
		text-decoration: line-through;
		padding-left: 12rpx;
	}

	.tui-pro-pay {
		padding-top: 10rpx;
		font-size: 24rpx;
		color: #656565;
	}
</style>
