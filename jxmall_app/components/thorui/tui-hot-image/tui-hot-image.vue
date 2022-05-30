<template>
	<view style="position: relative;">
		<image :src="value.pic_url" mode="widthFix" style="width: 100%;border-radius: 10rpx;overflow: hidden;"></image>
		<block v-if="value.list.length" v-for="item in value.list">
			<view @click="pageTo(item.url)"
				:style="{left:item.starX+'rpx',top:item.starY+'rpx',width:item.areaWidth+'rpx',height:item.areaHeight+'rpx'}"
				style="position: absolute;">
				 
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
					return {
						hot_image_id: '',
						list: [],
						pic_url: '',
						name: ''
					};
				}
			}
		},
		data() {
			return {

			}
		},
		mounted() {
		},
		methods: {
			pageTo(url) {
				console.log(url);
				this.$T.go(url)
			}
		}
	}
</script>

<style>

</style>
