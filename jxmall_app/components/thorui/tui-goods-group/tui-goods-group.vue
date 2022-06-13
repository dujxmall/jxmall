<template>
	<view>
		<block v-if="value.type==1">
			<scroll-view scroll-x="true" style="width: 100%;white-space: nowrap;">
				<!-- 左右滑动-->
				<view class="good-box-item" style="margin-right: 3rpx;background-color: #FFFFFF;display: inline-block;"
					@click="pageTo(goods)" :class="'goods-item-width-'+value.columns" v-if="value.list.length>0"
					v-for="(goods,index) in value.list">
					<view style="padding-bottom: 5rpx;">
						<view class="good-img-box" :style="goodsHeight">
							<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
						</view>
						<view>
							<text class="title">
								{{goods.name}}
							</text>
						</view>
						<view class="box-bottom flex-row flex-x-between">
							<view class="price" :style="{color:value.color}">
								￥{{goods.price}}</view>

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
						<view style="text-align: start;padding: 0 16rpx;padding-bottom: 10rpx;" class="sales"
							v-if="value.show_sales==1">
							<text> {{value.sales_text}}</text> <text style="padding: 0 5rpx;"
								:style="{color:value.price_color}">{{goods.virtual_sales}}</text>
						</view>
					</view>

				</view>
			</scroll-view>
		</block>

		<block v-if="value.type==0">

			<!--
				直接显示的 
				 -->
			<block v-if="value.columns===0">
				<view class="good-box-item" @click.stop.prevent="pageTo(goods)" v-if="value.list.length>0"
					v-for="(goods,index) in value.list" style="width: 100%;margin-bottom: 10rpx;">
					<view class="flex-row flex-x-center">
						<view class="goods-img-box">
							<image :src="goods.cover_pic" mode="aspectFill" class="img" style="width: 300rpx;"
								:style="goodsHeight"></image>
						</view>
						<view
							style="width: 100%;padding: 0 10rpx;position: relative;overflow: hidden;padding-bottom: 5rpx;box-sizing: border-box;">
							<view style="position: absolute;top: 0;left: 0;width: 100%;">
								<view
									style="padding: 10rpx;text-overflow: ellipsis;font-weight: 500;font-size: 12pt;font-weight: bold;">
									{{goods.name}}
								</view>
							</view>
							<view style="position: absolute;bottom: 20rpx;left: 0;width: 100%;padding: 0 20rpx;"
								class="flex-x-between flex-row">
								<view style="padding: 10rpx;text-overflow: ellipsis;color: #FF5555;">
									<block v-if="goods.is_negotiable==1">
										<view style="color: #FF5555;font-size: 16px;font-weight: bold;"
											:style="{color:value.color}">面议</view>
									</block>
									<block v-else>
										<view style="color: #FF5555;font-size: 16px;font-weight: bold;"
											:style="{color:value.color}">
											￥{{goods.price}}</view>
									</block>
								</view>

								<block v-if="value.show_buy_btn==1">
									<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
									</image>
									<view v-if="value.cart_type==0"
										:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.buy_btn_color+ ' solid 1rpx'}"
										style="padding: 5rpx 15rpx;margin-bottom: 10rpx;">
										{{value.cart_text}}
									</view>
								</block>
							</view>

							<view style="text-align: start;padding: 0 16rpx;" class="sales" v-if="value.show_sales==1">
								<text> {{value.sales_text}}</text><text style="padding: 0 5rpx;"
									:style="{color:value.price_color}">{{goods.virtual_sales}}</text>
							</view>
						</view>
					</view>
				</view>
			</block>


			<view v-if="value.columns==1">
				<view class="good-box-item" @click="pageTo(goods)" v-if="value.list.length>0"
					v-for="(goods,index) in value.list"
					style="width:100%;background-color: #FFFFFF;padding-bottom: 6rpx;"
					:style="{marginBottom:index<value.list.length-1?'10rpx':'0rpx'}">
					<view class="good-img-box" :style="goodsHeight">
						<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
					</view>
					<view>
						<text class="title">
							{{goods.name}}
						</text>
					</view>
					<view class="box-bottom flex-row flex-x-between">
						<block v-if="goods.is_negotiable==1">
							<view class="price" :style="{color:value.color}">面议</view>
						</block>
						<block v-else>
							<view class="price" :style="{color:value.color}">
								￥{{goods.price}}</view>

						</block>
						<view class="btn" v-if="value.show_buy_btn==1">
							<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
							</image>
							<view v-if="value.cart_type==0"
								:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.cart_color+ ' solid 1rpx'}"
								style="padding: 2rpx 10rpx;font-size: 20rpx;margin-bottom: 10rpx;">
								{{value.cart_text}}
							</view>
						</view>
					</view>
					<view style="text-align: start;padding: 0 16rpx;box-sizing: border-box;" class="sales"
						v-if="value.show_sales==1">
						<text> {{value.sales_text}}</text><text style="padding: 0 5rpx;"
							:style="{color:value.price_color}">{{goods.virtual_sales}}</text>
					</view>
				</view>
			</view>
			<view v-if="value.columns>1">
				<uni-grid :showBorder="false" :column="value.columns" :square="false">
					<block v-if="value.list.length>0" v-for="(goods,index) in value.list">
						<block v-if="value.columns==2">
							<uni-grid-item
								style="overflow: hidden;box-sizing: border-box;text-align: center;margin-top: 2px;box-sizing: border-box;"
								:style="{marginRight:index%2==0?'3rpx':'0rpx'}">
								<view class="good-box-item" @click="pageTo(goods)"
									style="width:100%;overflow: hidden;margin: 0 auto; margin-bottom: 3rpx;border-radius: 15rpx;background-color: #FFFFFF;">
									<view style="box-sizing: border-box;position: relative;padding-bottom: 5rpx;">
										<view class="good-img-box" :style="goodsHeight">
											<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
										</view>
										<view>
											<text class="title">
												{{goods.name}}
											</text>
										</view>

										<view class="box-bottom flex-row flex-x-between flex-y-center">
											<view class="price" :style="{color:value.price_color}"
												v-if="goods.is_negotiable==1">
												面议</view>
											<view class="price" :style="{color:value.price_color}" v-else>
												￥{{goods.price}}
											</view>
											<view class="btn" v-if="value.show_buy_btn==1">
												<image :src="value.cart_pic" v-if="value.cart_type==1"
													class="buybtn-icon">
												</image>
												<view v-if="value.cart_type==0"
													:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.cart_color+ ' solid 1rpx'}"
													style="padding: 2rpx 10rpx;font-size: 20rpx;margin-bottom: 10rpx;">
													{{value.cart_text}}
												</view>
											</view>
										</view>
										<view style="text-align: start;padding: 0 16rpx;padding-bottom: 10rpx;"
											class="sales" v-if="value.show_sales==1">
											<text> {{value.sales_text}}</text><text style="padding: 0 5rpx;"
												:style="{color:value.price_color}">{{goods.virtual_sales}}</text>
										</view>
									</view>
								</view>
							</uni-grid-item>

						</block>

						<template v-if="value.columns>2">
							<uni-grid-item
								style="overflow: hidden;box-sizing: border-box;text-align: center;margin-top: 2px;box-sizing: border-box;"
								:style="{padding:index==1?'0 4rpx':'0rpx'}">
								<view class="good-box-item" @click="pageTo(goods)"
									style="width:100%;overflow: hidden;margin: 0 auto; margin-bottom: 3rpx;border-radius: 15rpx;background-color: #FFFFFF;">
									<view style="box-sizing: border-box;position: relative;padding-bottom: 5rpx;">
										<view class="good-img-box" :style="goodsHeight">
											<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
										</view>
										<view>
											<text class="title">
												{{goods.name}}
											</text>
										</view>

										<view class="box-bottom flex-row flex-x-between flex-y-center">
											<view class="price" :style="{color:value.price_color}"
												v-if="goods.is_negotiable==1">
												面议</view>
											<view class="price" :style="{color:value.price_color}" v-else>
												￥{{goods.price}}
											</view>
											<view class="btn" v-if="value.show_buy_btn==1">
												<image :src="value.cart_pic" v-if="value.cart_type==1"
													class="buybtn-icon">
												</image>
												<view v-if="value.cart_type==0"
													:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.cart_color+ ' solid 1rpx'}"
													style="padding: 2rpx 10rpx;font-size: 20rpx;margin-bottom: 10rpx;">
													{{value.cart_text}}
												</view>
											</view>
										</view>
										<view style="text-align: start;padding: 0 16rpx;padding-bottom: 10rpx;"
											class="sales" v-if="value.show_sales==1">
											<text> {{value.sales_text}}</text><text style="padding: 0 5rpx;"
												:style="{color:value.price_color}">{{goods.virtual_sales}}</text>
										</view>
									</view>
								</view>
							</uni-grid-item>

						</template>

					</block>

				</uni-grid>
			</view>
		</block>


		<block v-if="value.type==2">
			<!-- 直接显示的-->
			<view class="good-box-item" @click="pageTo(goods)" v-if="value.list.length>0"
				v-for="(goods,index) in value.list"
				style="width:100%;background-color: #FFFFFF;border-radius: 0;padding:5rpx 0;padding-right: 20rpx;"
				:style="{borderBottom:index<value.list.length-1?'solid #f0f0f0 1rpx':'none'}">
				<view class="flex-row">
					<view style="width: 25%;">
						<view class="good-img-box" :style="goodsHeight" style="width: 100%;">
							<image :src="goods.cover_pic" mode="aspectFill" class="img"></image>
						</view>
					</view>
					<view style="width: 75%;">
						<view class="title">
							{{goods.name}}
						</view>
						<view class="box-bottom flex-row flex-x-between" style="width: 100%;margin-top: 30rpx;">
							<view class='flex-y-center'>
								<block v-if="goods.is_negotiable==1">
									<view class="price" :style="{color:value.color}">面议</view>
								</block>
								<block v-else>
									<view class="price" :style="{color:value.color}">
										￥{{goods.price}}</view>
								</block>
								<view class="sales" style="text-align: start;padding: 0 16rpx;padding-bottom: 0rpx;"
									v-if="value.show_sales==1">
									<text> {{value.sales_text}}</text><text style="padding: 0 5rpx;"
										:style="{color:value.price_color}">{{goods.virtual_sales}}</text>
								</view>
							</view>
							<view class="btn" v-if="value.show_buy_btn==1">
								<image :src="value.cart_pic" v-if="value.cart_type==1" class="buybtn-icon">
								</image>
								<view v-if="value.cart_type==0"
									:style="{color:value.cart_color,borderRadius:value.is_circle==1?'30rpx':'6rpx' ,border:value.cart_color+ ' solid 1rpx'}"
									style="padding: 2rpx 10rpx;font-size: 20rpx;margin-bottom: 10rpx;">
									{{value.cart_text}}
								</view>
							</view>
						</view>

					</view>
				</view>
			</view>
		</block>
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
		computed: {
			goodsHeight() {
				return `height:${this.value.cover_pic_height}rpx`;

			},
		},
		methods: {
			pageTo(row) {
				uni.navigateTo({
					url: "/pages/goods/goods?id=" + row.id
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
		width: 30%
	}

	.good-box-item {
		overflow: hidden;
		box-sizing: border-box;
		border-radius: 10rpx;

		.sales {
			color: #606266;
			font-size: 9pt;
		}

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
			padding: 0 10rpx;
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
			padding: 0 10rpx;
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
