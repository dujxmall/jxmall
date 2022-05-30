<template>
	<view>
		<view>
			<block v-if="value.type==1">
				<scroll-view scroll-x="true" style="width: 100%;white-space: nowrap;">
					<!-- 
	 左右滑动-->
					<view>
						<view class="good-box-item" style="background-color: #FFFFFF;margin-right: 20rpx;"
							@click="$T.go(goods.url)" :class="'goods-item-width-'+value.columns"
							v-if="value.list.length>0" v-for="(goods,index) in value.list">
							<view>
								<view class="good-img-box" :style="{height:value.columns==3?'250rpx':''}">
									<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
								</view>
								<view>
									<text class="title">
										{{goods.name}}
									</text>
								</view>
								<view class="box-bottom flex-row flex-x-between">
									<view class="price" :style="{color:value.color}">{{goods.price}}</view>
									<view class="btn" v-if="value.show_buy_btn==1">
										<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
										</image>
										<view v-if="value.cart_type==0"
											:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.buy_btn_color+ ' solid 1rpx'}"
											style="padding: 5rpx 15rpx;">
											{{value.cart_text}}
										</view>
									</view>
								</view>
							</view>
						</view>

					</view>
				</scroll-view>

			</block>

			<block v-if="value.type==0">

				<!--
				直接显示的 
				 -->
				<view v-if="value.columns===0">
					<view class="good-box-item" @click.stop.prevent="$T.go(goods.url)" v-if="value.list.length>0"
						v-for="(goods,index) in value.list" style="width: 100%;">
						<view class="flex-row flex-x-center">
							<view class="goods-img-box">
								<image :src="goods.cover_pic" mode="aspectFill" class="img"
									style="width: 300rpx;height: 300rpx;"></image>
							</view>
							<view style="width: 100%;padding: 0 10rpx;position: relative;overflow: hidden;">
								<view style="position: absolute;top: 0;left: 0;width: 100%;">
									<view
										style="padding: 10rpx;text-overflow: ellipsis;font-weight: 500;font-size: 12pt;font-weight: bold;">
										{{goods.name}}
									</view>
								</view>
								<view style="position: absolute;bottom: 20rpx;left: 0;width: 100%;padding: 0 20rpx;"
									class="flex-x-between flex-row">
									<view style="padding: 10rpx;text-overflow: ellipsis;color: #FF5555;">
										<view style="color: #FF5555;font-size: 16px;font-weight: bold;"
											:style="{color:value.color}" v-if="goods.is_negotiable==1">面议</view>
										<view style="color: #FF5555;font-size: 16px;font-weight: bold;"
											:style="{color:value.color}" v-else>
											{{goods.price}}
										</view>
									</view>
									<block v-if="value.show_buy_btn==1">
										<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
										</image>
										<view v-if="value.cart_type==0"
											:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.buy_btn_color+ ' solid 1rpx'}"
											style="padding: 5rpx 15rpx;">
											{{value.cart_text}}
										</view>
									</block>
								</view>
							</view>
						</view>
					</view>
				</view>


				<view v-if="value.columns==1">
					<view class="good-box-item" @click="$T.go(goods.url)" v-if="value.list.length>0"
						v-for="(goods,index) in value.list"
						style="width:100%;margin: 0 5rpx;background-color: #FFFFFF;">
						<view>
							<view class="good-img-box" style="min-height: 475rpx;">
								<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
							</view>
							<view>
								<text class="title">
									{{goods.name}}
								</text>
							</view>
							<view class="box-bottom flex-row flex-x-between">
								<view class="price" :style="{color:value.color}" v-if="goods.is_negotiable==1">面议</view>
								<view class="price" :style="{color:value.color}" v-else>{{goods.price}}</view>
								<view class="btn" v-if="value.show_buy_btn==1">
									<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
									</image>
									<view v-if="value.cart_type==0"
										:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.cart_color+ ' solid 1rpx'}"
										style="padding: 2rpx 10rpx;font-size: 20rpx;">
										{{value.cart_text}}
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>

				<view v-if="value.columns>1">
					<tui-grid style="justify-content: start;">
						<tui-grid-item :backgroundColor="''" :cell="value.columns" @click="$T.go(goods.url)"
							v-if="value.list.length>0" v-for="(goods,index) in value.list"
							style="border-radius: 20rpx;overflow: hidden;box-sizing: border-box;background: none;text-align: center;margin-top: 2px;">
							<view class="good-box-item"
								style="width:100%;overflow: hidden;margin: 0 auto;margin-bottom: 3rpx;">
								<view style="background-color: #fff;border-radius: 10rpx;">
									<view class="good-img-box" :style="{height:value.columns==3?'275rpx':'375rpx'}">
										<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
									</view>
									<view>
										<text class="title">
											{{goods.name}}
										</text>
									</view>
									<view class="box-bottom flex-row flex-x-between">
										<view class="price" :style="{color:value.color}" v-if="goods.is_negotiable==1">
											面议</view>
										<view class="price" :style="{color:value.color}" v-else>{{goods.price}}</view>
										<view class="btn" v-if="value.show_buy_btn==1">
											<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
											</image>
											<view v-if="value.cart_type==0"
												:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.cart_color+ ' solid 1rpx'}"
												style="padding: 2rpx 10rpx;font-size: 20rpx;">
												{{value.cart_text}}
											</view>
										</view>
									</view>
								</view>
							</view>
						</tui-grid-item>
					</tui-grid>
				</view>
			</block>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			value: {
				type: Object,
				default: () => {
					return null
				}
			},
		},
		data() {
			return {


			}
		},
		watch: {
			value(newVal, oldVal) {

			}
		},
		methods: {
			pageTo(row) {
				uni.navigateTo({
					url: row.url
				})
			}
		}
	}
</script>

<style lang="scss">
	::-webkit-scrollbar {
		width: 0;
		height: 0;
		color: transparent;
	}

	.goods-item-width-1 {
		width: 85%;
	}

	.goods-item-width-2 {
		width: 40%
	}

	.goods-item-width-3 {
		width: 25%
	}

	.good-box-item {
		border-radius: 10rpx;
		overflow: hidden;
		padding-right: 6rpx;
		padding-left: 6rpx;
		display: inline-block;
		box-sizing: border-box;

		.good-img-box {
			overflow: hidden;
			height: 370rpx;

			.img {
				height: 100%;
				width: 100%;

			}
		}

		.title {
			width: 100%;
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
			color: #212121;
			font-family: PingFang SC;
			font-style: normal;
			font-weight: normal;
			font-size: 14px;
			line-height: 20px;
			max-height: 40px;
			padding: 0 8px;
			margin: 8px 0 4px;
			overflow: hidden;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-box-orient: vertical;
			-webkit-line-clamp: 2;
			white-space: normal;
			text-align: start;
			height: 75rpx;
		}

		.box-bottom {
			padding: 0 8px 8px;
			-webkit-box-pack: justify;
			-ms-flex-pack: justify;
			justify-content: space-between;

			.price {
				color: #FF3C29;
			}

			.btn {
				.buybtn-icon {
					width: 48rpx;
					height: 48rpx;
				}
			}
		}

	}
</style>
