<template>
	<tui-page style="overflow: hidden;padding-top: 10rpx;">
		<view class="tui-product-list" v-if="list.length>0">
			<view class="tui-product-container">
				<uni-grid :column="2" :square="false" :showBorder="false" :highlight="false">
					<block v-for="(item,index) in list" :key="index">
						<!--商品列表-->
						<uni-grid-item style="padding:0 5rpx;">
							<tui-goods-item :goods="item"></tui-goods-item>
						</uni-grid-item>
						<!--商品列表-->
					</block>
				</uni-grid>
			</view>
		</view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				page: 1,
				is_no_more: false,
				list: [],
				
			}
		},
		onShow: function() {
			this.getList();
		},
		onReachBottom: function() {
			this.getList();
		},

		methods: {

			detail(row) {
				console.log(row);
				uni.redirectTo({
					url: "/pages/goods/goods?id=" + row.id
				})
			},
			getList() {
				if (this.is_no_more) {
					return;
				}
				this.$request({
					url: '/api/user/goods-footmark',
					data:{
						page: this.page,
					}
				}).then(res => {
					if (res.code == 0) {

						if (this.page == 1) {
							this.list = res.data.list;
						} else {
							this.list = this.list.concat(res.data.list);
						}

						if (this.page >= res.data.pagination.page_count) {
							this.is_no_more = true;
						} else {
							this.page++;

						}


					}

				})

			},

		}
	}
</script>

<style>
	/*为你推荐*/
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
		height: 375rpx;
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
		color: #05646A;
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

	.tui-btn-back {
		width: 88rpx;
		height: 88rpx;
		font-size: 26rpx;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
		background-color: rgba(0, 0, 0, .5);
		color: #fff;
		position: fixed;
		bottom: 160rpx;
		right: 30rpx;
		z-index: 9999;
	}
</style>
