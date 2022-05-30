<template>
	<view>
		<view>
			<block v-if="value.type==1">
				<scroll-view scroll-x="true" style="width: 100%;white-space: nowrap;">
					<!-- 
	 左右滑动-->
					<view>
						<view class="good-box-item" @click="pageTo(goods)" :class="'goods-item-width-'+value.columns"
							v-if="value.list.length>0" v-for="(goods,index) in value.list">
							<view>
								<view class="good-img-box" :style="{height:value.columns==3?'270rpx':''}">
									<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
								</view>
								<view>
									<view class="title">
										{{goods.name}}
									</view>
									<view :style="{color:value.success_num_color}"
										style="padding: 0 8px;font-size: 10pt;" v-if="value.show_success_num==1">
										{{goods.success_num}}人团
									</view>
								</view>
								<view class="box-bottom flex-row flex-x-between flex-x-bottom">
									<view class="price" style="font-weight: bold;" :style="{color:value.color}">
										￥{{goods.price}}</view>
									<view class="btn" style="font-size: 9pt;">
										<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
										</image>
										<view v-if="value.cart_type==0"
											:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.buy_btn_color+ ' solid 1rpx'}"
											style="padding: 5rpx 15rpx;font-size: 9pt;margin-bottom: 10rpx;"
											class="flex-y-center flex-x-center">
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
					<view class="good-box-item" @click.stop.prevent="pageTo(goods)" v-if="value.list.length>0"
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
									<view :style="{color:value.success_num_color}" v-if="value.show_success_num==1">
										{{goods.success_num}}
									</view>
								</view>
								<view style="position: absolute;bottom: 20rpx;left: 0;width: 100%;padding: 0 20rpx;"
									class="flex-x-between flex-row">
									<view style="padding: 10rpx;text-overflow: ellipsis;color: #FF5555;">

										<view style="color: #FF5555;font-size: 16px;font-weight: bold;"
											:style="{color:value.color}">
											￥{{goods.price}}</view>
									</view>
									<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
									</image>
									<view v-if="value.cart_type==0"
										:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.buy_btn_color+ ' solid 1rpx'}"
										style="padding: 5rpx 15rpx;margin-bottom: 10rpx;" class="flex-y-center flex-x-center">
										{{value.cart_text}}
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>


				<view v-if="value.columns==1">
					<view class="good-box-item" @click="pageTo(goods)" v-if="value.list.length>0"
						v-for="(goods,index) in value.list"
						style="width:100%;margin: 0 5rpx;background-color: #FFFFFF;">
						<view>
							<view class="good-img-box" style="min-height: 475rpx;">
								<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
							</view>
							<view>
								<view class="title">
									{{goods.name}}
								</view>
								<view :style="{color:value.success_num_color}" style="font-size: 9pt;"
									v-if="value.show_success_num==1">
									{{goods.success_num}}人团
								</view>
							</view>
							<view class="box-bottom flex-row flex-x-between">
								<view class="price" :style="{color:value.color}">￥{{goods.price}}</view>
								<view class="btn">
									<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
									</image>
									<view v-if="value.cart_type==0"
										:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.cart_color+ ' solid 1rpx'}"
										style="padding: 2rpx 10rpx;font-size: 20rpx;margin-bottom: 10rpx;"
										class="flex-y-center flex-x-center">
										{{value.cart_text}}
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>



				<view v-if="value.columns>1">
					<uni-grid :showBorder="false" :column="value.columns" :square="false">
						<uni-grid-item v-if="value.list.length>0" v-for="(goods,index) in value.list"
							style="overflow: hidden;box-sizing: border-box;text-align: center;margin-top: 2px;box-sizing: border-box;">
							<view class="good-box-item" @click="pageTo(goods)"
								style="width:98%;overflow: hidden;margin: 0 auto; margin-bottom: 3rpx;border-radius: 15rpx;background-color: #FFFFFF;">
								<view style="box-sizing: border-box;position: relative;">
									<view class="good-img-box" :style="{height:value.columns==3?'275rpx':'375rpx'}">
										<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
									</view>
									<view style="text-align: start;">
										<view class="title">
											{{goods.name}}
										</view>
										<view :style="{color:value.success_num_color}"
											style="font-size: 9pt;padding: 0 8px;width: 100%;box-sizing: border-box;"
											v-if="value.show_success_num==1">
											{{goods.success_num}}人团
										</view>
									</view>
									<view class="box-bottom flex-row flex-x-between"
										style="padding-right: 10rpx;padding-bottom: 10rpx;box-sizing: border-box;">
										<view class="price" :style="{color:value.color}">￥{{goods.price}}</view>
										<view class="btn">
											<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
											</image>
											<view v-if="value.cart_type==0"
												:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.cart_color+ ' solid 1rpx'}"
												style="padding: 2rpx 10rpx;font-size: 20rpx;box-sizing: border-box;margin-bottom: 10rpx;"
												class="flex-y-center flex-x-center">
												{{value.cart_text}}
											</view>
										</view>
									</view>
								</view>
							</view>
						</uni-grid-item>
					</uni-grid>
				</view>
			</block>


			<block v-if="value.type==2">
				<!--
				直接显示的 
				 -->

				<view class="good-box-item" @click="pageTo(goods)" v-if="value.list.length>0"
					v-for="(goods,index) in value.list"
					style="width:100%;margin: 0 5rpx;background-color: #FFFFFF;padding: 20rpx;">
					<view class="flex-row">
						<view style="width: 270rpx;">
							<view class="good-img-box" style="width: 250rpx;height: 250rpx;">
								<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
							</view>
						</view>
						<view style="width:100%;position: relative;">
							<view class="title" style="width: 395rpx;">
								{{goods.name}}{{goods.name}}{{goods.name}}{{goods.name}}{{goods.name}}{{goods.name}}{{goods.name}}{{goods.name}}{{goods.name}}{{goods.name}}
							</view>
							<view style="position: absolute;bottom: 10rpx;left: 10rpx;width: 100%;">
								<view class="box-bottom flex-row flex-x-between"
									style="width: 100%;padding-right: 20rpx;box-sizing: border-box;">
									<view style="width: 100%;">
										<view style="padding: 10rpx;font-weight: 600;"
											:style="{color:value.success_num_color}" v-if="value.show_success_num==1">
											{{goods.success_num}}人团
										</view>
										<view class="flex-x-between" style="width: 100%;">
											<view class="btn">
												<image :src="value.cart_pic" v-if="value.cart_type==1"
													class="buybtn-icon">
												</image>
												<view class="price" :style="{color:value.color}">￥{{goods.price}}</view>
											</view>
											<view v-if="value.cart_type==0"
												:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.cart_color+ ' solid 1rpx'}"
												style="padding: 2rpx 10rpx;font-size: 20rpx;margin-bottom: 10rpx;"
												class="flex-y-center flex-x-center">
												{{value.cart_text}}
											</view>
										</view>
									</view>
								</view>
							</view>
						</view>
					</view>
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
					url: "/plugins/pt/goods?id=" + row.id
				})
			}
		}
	}
</script>

<style lang="scss">
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
		display: inline-block;
		box-sizing: border-box;

		.good-img-box {
			overflow: hidden;
			height: 370rpx;
			box-sizing: border-box;

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
			word-wrap: break-word;
			word-break: break-all;

		}

		.box-bottom {

			-webkit-box-pack: justify;
			-ms-flex-pack: justify;
			justify-content: space-between;

			.price {
				color: #FF3C29;
				font-weight: bold;
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
