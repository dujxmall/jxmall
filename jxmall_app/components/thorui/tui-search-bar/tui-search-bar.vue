<template>
	<view>
		<view class="tui-searchbox">
			<view class="tui-rolling-search"  @click.stop="searchClick" :style="{borderRadius: value.is_circle?'35rpx':'10rpx',backgroundColor:value.background}">
				<icon type="search" :size='13' color='#999'></icon>
				<view style="font-size: 10pt;color: #A1A1A1;padding: 0 20rpx;">{{value.tips}}</view>
			</view>
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

			}
		},
		methods: {
			searchClick(){
				uni.navigateTo({
					url:'/pages/search/search'
				})
			},

		}
	}
</script>

<style>
	
		
	.circle{
		border-radius: 50rpx;
	}
	.tui-searchbox {
 
		box-sizing: border-box;
		background-color: #F2F2F2;
	}

	.tui-rolling-search {
		width: 100%;
		height: 65rpx;
	
		padding: 0 40rpx 0 30rpx;
		box-sizing: border-box;
		background: #fff;
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
		color: #999;
	}
</style>
