<template>
	<view>
		<web-view  :src="url"></web-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				url: '',
			}
		},
		onLoad(e) {
			if(!e.url||e.url==''){
				uni.showModal({
					title:'提示',
					content:'未传入链接',
					confirmText:'我知道了',
					showCancel:false
				})
				return;
				
			}
			this.url = e.url;
		},
		methods: {

		}
	}
</script>

<style>

</style>
