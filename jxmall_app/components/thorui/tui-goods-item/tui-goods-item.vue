<template>
	<view class="tui-pro-item" @click="$T.go('/pages/goods/goods?id='+goods.id)">
		<image :src="goods.cover_pic" class="tui-pro-img"
			mode="scaleToFill" />
		<view class="tui-pro-content">
			<view class="tui-pro-tit">{{ goods.name }}</view>
			<view>
				<view class="tui-pro-price" v-if="goods.is_negotiable==0">
					<view class="tui-sale-price">￥{{ goods.price }}</view>
					<view class="tui-factory-price">￥{{ goods.origin_price }}</view>
				</view>
				<view class="tui-pro-price" v-if="goods.is_negotiable==1">
					<text class="tui-sale-price">面议</text>
				</view>
				<view class="tui-pro-pay"> 销量：{{ goods.virtual_sales }} {{goods.unit}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "TuiGoodsItem",
		data() {
			return {

			}
		},
		props: {
			goods: {
				type: Object,
				default: null
			}
		},
		methods: {

		}
	}
</script>

<style>
	.tui-pro-item {
		width: 100%;
		margin-bottom: 10rpx;
		background: #fff;
		box-sizing: border-box;
		border-radius: 12rpx;
		overflow: hidden;
		transition: all 0.15s ease-in-out;
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
		font-weight: bold;
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
		font-weight: bold;
		color: #e41f19 !important;
	}
	
	.tui-factory-price {
		font-size: 24rpx;
		color: #a0a0a0;
		text-decoration: line-through;
		padding-left: 2rpx;
		 
	}
	
	.tui-pro-pay {
		padding-top: 10rpx;
		font-size: 24rpx;
		color: #656565;
	}
</style>
