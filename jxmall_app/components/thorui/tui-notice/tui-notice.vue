<template>
	<view>
		<view class="rolling-search" :style="{backgroundColor:value.bg_color,color:value.text_color}"
			v-if="value.type==0">
			<image :src="value.icon" style="width: 20px;height: 20px;margin-right: 10rpx;" mode="aspectFill"></image>
			<swiper vertical autoplay circular :interval="3000" class="tui-swiper">
				<swiper-item v-for="(item,index) in value.list" :key="index" class="tui-swiper-item">
					<view class="news-item" @tap='detail' :style="{color:value.text_color}">{{item.content}}</view>
				</swiper-item>
			</swiper>
		</view>
		<view class='tui-notice-board rolling-search' :style="{backgroundColor:value.bg_color}" v-if="value.type==1">
			<view class="tui-icon-bg flex-y-center">
				<image :src="value.icon" style="width: 20px;height: 20px;margin-right: 10rpx;" mode="aspectFill">
				</image>
			</view>
			<view class="tui-scorll-view" @tap='detail'>
				<view class="tui-notice tui-animation" :style="{color:value.text_color}">{{value.list[0].content}}
				</view>
			</view>
		</view>

		<!--搜索框-->
		<view class="tui-rolling-news" :style="{backgroundColor:value.bg_color,color:value.text_color}"
			v-if="value.type==2">
			<image :src="value.icon" style="width: 20px;height: 20px;margin-right: 10rpx;" mode="aspectFill"></image>
			<swiper autoplay circular :interval="3000" class="tui-swiper">
				<swiper-item v-for="(item,index) in value.list" :key="index" class="tui-swiper-item">
					<view class="news-item" @tap='detail' :style="{color:value.text_color}">{{item.content}}</view>
				</swiper-item>
			</swiper>
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
			}
		},
		data() {
			return {

				animation: true
			}
		},
		created: function(options) {

			setTimeout(() => {
				this.animation = true
			}, 600)
		},
		methods: {
			detail(e) {
				this.tui.toast('详情功能尚未完善~')
			}
		}
	}
</script>

<style>
	.container {
		padding-top: 120rpx;
	}

	.tui-notice-board {
		width: 100%;
		padding-right: 30rpx;
		box-sizing: border-box;
		font-size: 28rpx;
		height: 60rpx;
		display: flex;
		align-items: center;
		z-index: 999;
	}

	.tui-icon-bg {


		position: relative;
		z-index: 10;
	}

	.tui-icon-class {
		margin-right: 12rpx;
	}

	.tui-scorll-view {
		flex: 1;
		line-height: 1;
		white-space: nowrap;
		overflow: hidden;
		color: #f54f46;
	}

	.tui-notice {
		-webkit-backface-visibility: hidden;
		-webkit-perspective: 1000;
		transform: translate3d(100%, 0, 0);
	}

	.tui-animation {
		-webkit-animation: tui-rolling 12s linear infinite;
		animation: tui-rolling 12s linear infinite;
	}

	@-webkit-keyframes tui-rolling {
		0% {
			transform: translate3d(100%, 0, 0);
		}

		100% {
			transform: translate3d(-170%, 0, 0);
		}
	}

	@keyframes tui-rolling {
		0% {
			transform: translate3d(100%, 0, 0);
		}

		100% {
			transform: translate3d(-170%, 0, 0);
		}
	}

	.tui-subject {
		padding: 100rpx 30rpx 30rpx 30rpx;
		box-sizing: border-box;
		font-size: 32rpx;
		font-weight: bold;
	}

	.tui-rolling-news {
		width: 100%;
		padding: 12rpx 30rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-wrap: nowrap;
	}

	.tui-swiper {
		font-size: 28rpx;
		height: 50rpx;
		flex: 1;
	}

	.tui-swiper-item {
		display: flex;
		align-items: center
	}

	.news-item {
		line-height: 28rpx;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.tui-searchbox {
		padding: 60rpx 80rpx;
		box-sizing: border-box;
	}

	.rolling-search {
		width: 100%;
		height: 70rpx;
		border-radius: 10rpx;
		padding: 0 40rpx 0 30rpx;
		box-sizing: border-box;
		background: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-wrap: nowrap;
		color: #999;
	}
</style>
