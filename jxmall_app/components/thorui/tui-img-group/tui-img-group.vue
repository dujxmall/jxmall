<template>
	<view style="">

		<view style="width: 100%; border-radius: 10rpx;overflow: hidden;" :style="{height:value.height+'rpx'}"
			v-if="value.type==1&&value.list.length>0">
			<image :src="value.list[0].pic_url" mode="scaleToFill" style="width: 100%;height: 100%;"
				@click="$T.go(value.list[0].url)"></image>
		</view>
		<view style="width: 100%; border-radius: 10rpx;overflow: hidden;" :style="{height:value.height+'rpx'}"
			class="flex-row" v-if="value.type==2&&value.list.length>0">
			<view style="width:50%;">
				<image :src="value.list[0].pic_url" mode="scaleToFill" style="width: 100%;height: 100%;"
					@click="$T.go(value.list[0].url)"></image>
			</view>
			<view style="width:50%;">
				<image :src="value.list[1].pic_url" mode="scaleToFill" style="width: 100%;height: 100%;"
					@click="$T.go(value.list[1].url)"></image>
			</view>
		</view>
		<view style="width: 100%; border-radius: 10rpx;overflow: hidden;" :style="{height:value.height+'rpx'}"
			class="flex-row" v-if="value.type==3&&value.list.length>0">
			<view style="width:50%;">
				<image :src="value.list[0].pic_url" mode="scaleToFill" style="width: 100%;height: 100%;"
					@click="$T.go(value.list[0].url)"></image>
			</view>
			<view style="width:50%;">
				<view style="height:50%;">
					<image :src="value.list[1].pic_url" mode="scaleToFill" style="width: 100%;height: 100%;"
						@click="$T.go(value.list[1].url)"></image>
				</view>
				<view style="height:50%;">
					<image :src="value.list[2].pic_url" mode="scaleToFill" style="width: 100%;height: 100%;"
						@click="$T.go(value.list[2].url)"></image>
				</view>
			</view>
		</view>
		<view style="width: 100%; border-radius: 10rpx;overflow: hidden;" :style="{height:value.height+'rpx'}"
			class="flex-row" v-if="value.type==4&&value.list.length>0">
			<view style="width:33.33333%;" v-for="item in value.list">
				<image :src="item.pic_url" mode="scaleToFill" style="width: 100%;height: 100%;" @click="$T.go(item.url)">
				</image>
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

			return {}
		},
		methods: {

		}
	}
</script>

<style>

</style>
