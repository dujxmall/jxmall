<template>
	<view :style="{'background-color': value.background}" style="padding: 10rpx 0;	">
		<tui-grid>
			<block v-for="(item,index) in value.list" :key="index">
				<tui-grid-item :cell="value.columns" @click="navClick(item)" class="nav">
					<image :src="item.pic_url" mode="aspectFill" class="icon"></image>
					<text class="name">{{item.name}}</text>
				</tui-grid-item>
			</block>
		</tui-grid>
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
			navClick(e) {
				if (!e.type || e.type == 'navigate') {
					uni.navigateTo({
						url: e.url
					})
				}
				if (e.type == 'redirect') {
					uni.redirectTo({
						url: e.url
					})
				}
				if (e.type == 'reLaunch') {
					uni.reLaunch({
						url: e.url
					})
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.tui-grids::after {
		
		border: none;
	}
	.nav {
		text-align: center;
		background-color: #FFFFFF;

		.icon {

			width: 87rpx;
			height: 87rpx;
		}

		.name {
			display: block;
			text-align: center;
			font-weight: 400;
			color: #333;
			font-size: 9pt;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
	}
</style>
