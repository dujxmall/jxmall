<template>
	<tui-page style="padding: 0 20rpx;">
		<view class="tui-order-item">
			<tui-list-cell :hover="false" :lineLeft="false">
				<view class="tui-goods-title">
					商品信息
				</view>
			</tui-list-cell>
			<block v-if="detail">
				<tui-list-cell padding="0">
					<view class="tui-goods-item">
						<image :src="goods.cover_pic" class="tui-goods-img"></image>
						<view class="tui-goods-center">
							<view class="tui-goods-name">{{goods.name}}</view>
							<view class="tui-goods-attr" v-if="detail.attr.length==0">规格：默认</view>
							<view class="tui-goods-attr" v-if="detail.attr.length>0" v-for="attr in detail.attr">
								{{attr.attr_group_name}}:{{attr.attr_name}}
							</view>
						</view>
						<view class="tui-price-right">
							<view>￥{{goods.price}}</view>
							<view>x{{detail.num}}</view>
						</view>
					</view>
				</tui-list-cell>
			</block>
			<view class="tui-goods-info">
				<view class="tui-price-flex tui-size32 tui-pbtm20">
					<view class="tui-flex-shrink">合计</view>
					<view class="tui-goods-price">
						<view class="tui-size-24">￥</view>
						<view class="tui-price-large">{{detail.price}}</view>
					</view>
				</view>
				<view class="tui-price-flex tui-size32">
					<view class="tui-flex-shrink">实付款</view>
					<view class="tui-goods-price tui-primary-color">
						<view class="tui-size-24">￥</view>
						<view class="tui-price-large">{{detail.price}}</view>
					</view>
				</view>
			</view>
		</view>
		<view class="tui-order-item">
			<tui-list-cell :hover="false" :lineLeft="false">
				<view class="flex-x-between">
					<view class="tui-goods-title">
						<view>售后类型</view>
						<view @click="selectType">{{type==0?'退货退款':'换货'}}</view>
					</view>
					<view class="">
						<tui-icon name="arrowright" color="#999" :size="20"></tui-icon>
					</view>
				</view>
			</tui-list-cell>

			<tui-list-cell :hover="false" :lineLeft="false">
				<view class="tui-goods-title">
					<view>插入图片</view>
				</view>
				<view class="upload">
					<tui-upload :serverUrl="'/api/upload/upload'" @complete="uploadComplete"></tui-upload>
				</view>
			</tui-list-cell>
			<tui-list-cell :hover="false" :lineLeft="false">
				<view class="tui-goods-title">
					说明原因
				</view>
			</tui-list-cell>
			<view style="padding: 20rpx;width: 100%;">
				<textarea style="width: 100%;" v-model="content" placeholder="输入原因" />
			</view>
			<view style="padding-bottom: 50rpx;text-align: center;width: 100%;">
				<view style="margin: 0 auto;width: 500rpx;">
					<tui-button type="danger" height="70rpx" :size="30" shape="circle" @click="submit">提交
					</tui-button>
				</view>
			</view>
		</view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				content: '',
				od_id: '',
				order_id: '',
				goods: '',
				detail: '',
				type: 0,
				pic_list: [],
			};
		},
		onLoad: function(options) {
			if (options.od_id && options.order_id) {
				this.od_id = options.od_id;
				this.order_id = options.order_id;
			} else {
				uni.showModal({
					title: '提示',
					content: '参数错误'
				})
				return
			}

			this.getDetail();
		},
		methods: {
			selectType() {
				let that = this;

				uni.showModal({
					title: '提示',
					content: '请选择售后类型',
					cancelText: '换货',
					confirmText: '退款退货',
					success: function(e) {
						if (e.confirm) {
							that.type = 0;

						} else {

							that.type = 1;
							console.log(that.type);
						}
					}
				})

			},
			uploadComplete(e) {

				this.pic_list = e.imgArr;
			},

			submit() {
				this.$request({
					url: '/api/order/refund',
					data: {
						order_id: this.order_id,
						order_detail_id: this.od_id,
						pic_list: JSON.stringify(this.pic_list),
						content: this.content,
						type: this.type
					},
					method: 'post'
				}).then(res => {

					uni.showModal({
						title: '提示',
						content: res.msg,
						success: function(e) {
							uni.navigateBack({
								delta: 1
							})
						}
					})


				})
			},
			getDetail() {
				this.$request({
					url: '/api/order/refund',
					data: {
						order_id: this.order_id,
						order_detail_id: this.od_id
					}
				}).then(res => {

					if (res.code == 0) {
						this.detail = res.data.detail;
						this.goods = res.data.goods;
					}
					console.log(res);
				})

			},

		}
	}
</script>

<style lang="scss">
	.tui-order-item {
		margin-top: 20rpx;
		border-radius: 10rpx;
		overflow: hidden;
		background-color: #FFFFFF;
		padding: 0 20rpx;
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

	.tui-goods-info {
		width: 100%;
		padding: 30rpx;
		box-sizing: border-box;
		background: #fff;
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

	.upload {
		margin-top: 20rpx;
		display: flex;
		position: relative;

		.upload-img {
			width: 180rpx;
			height: 180rpx;
			color: #bfbfbf;
			border: 1rpx dotted #bfbfbf;
			z-index: 10;

			.iconfont {
				font-size: 16pt;
			}
		}
	}
</style>
