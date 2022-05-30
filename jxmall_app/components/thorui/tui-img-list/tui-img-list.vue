<template>
	<view>
		<view style="padding: 0 10rpx;padding-top: 20rpx;background-color: #f3f3f3;">
		


			<block v-if="value.type==1">

				<scroll-view scroll-x="true" style="width: 100%;white-space: nowrap;">
					<!-- 
	 左右滑动-->
					<view>
						<view class="img-box-item " :class="'img-box-item-'+value.list_type" v-if="value.list.length>0" v-for="(item,index) in value.list">
							<view class="img-box" :style="{height:value.list_type==3?'270rpx':''}">
								<image :src="item.pic_url" mode="aspectFill" class="tui-article-img"></image>
								<view class="tui-article-title">{{item.sub_title}}</view>
							</view>
							<view style="padding: 0 20rpx;">
								<text class="title">
									{{item.title}}
								</text>
							</view>
						</view>
					</view>
				</scroll-view>
			</block>

			<block v-if="value.type==0">
				<!--
				直接显示的 
				 -->
				<view v-if="value.list_type==1">
					<view class="img-box-item" v-if="value.list.length>0" v-for="(item,index) in value.list" style="width:100%;margin: 0 5rpx;">
						<view class="img-box" style="min-height: 475rpx;">
							<image :src="item.pic_url" mode="aspectFill" class="img"></image>
							<view class="tui-article-title">{{item.sub_title}}</view>
						</view>
						<view>
							<text class="title">
								{{item.title}}
							</text>
						</view>
					</view>
				</view>

				<view v-if="value.list_type>1">
					<tui-grid>
						<tui-grid-item :cell="value.list_type" v-if="value.list.length>0" v-for="(item,index) in value.list" style="background-color: none;margin-bottom: 10rpx;border-radius: 10rpx;overflow: hidden;">
							<view class="img-box-item" style="width:100%;">
								<view class="img-box" :style="{height:value.list_type==3?'275rpx':'375rpx'}">
									<image :src="item.pic_url" mode="aspectFill" class="img"></image>
									<view class="tui-article-title">{{item.sub_title}}</view>
								</view>
								<view>
									<text class="title">
										{{item.title}}
									</text>
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
				console.log(newVal);

			}
		},
		methods: {

		}
	}
</script>

<style lang="scss">
	.tui-article {
		position: relative;
	}

	.tui-article-img {
		width: 100%;
		height: 100%;
		display: block;
	}

	.tui-article-title {
		position: absolute;
		width: 100%;
		left: 0;
		bottom: 0;
		color: #fff;
		font-size: 32rpx;
		font-weight: 500;
		box-sizing: border-box;
		padding: 20rpx 30rpx;
		background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.6));
	}

	.img-box-item-1 {
		width: 85%;
	}

	.img-box-item-2 {
		width: 40%
	}

	.img-box-item-3 {
		width: 25%
	}

	.img-box-item {
		border-radius: 10rpx;
		overflow: hidden;
		background-color: #FFFFFF;
		margin: 0 10rpx;


		display: inline-block;


		.img-box {

			position: relative;
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
