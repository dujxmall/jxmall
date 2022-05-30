<template>
	<view style="width: 96%;margin: 0 auto;border-radius: 10rpx;overflow: hidden; height: 100%;overflow: hidden;">
		<slot></slot>
	</view>
</template>

<script>
	export default {
		props: {
			marginTop: {
				type: Boolean,
				default: true
			},
			bgColor: {
				type: String,
				default: ''
			}
		},
		data() {
			return {

			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
