<template>
	<view>
		<web-view :src="url"></web-view>

	</view>
</template>

<script>
	export default {
		data() {
			return {
				id: '',
				url:'',
			}
		},
		onLoad(e) {
			if (e.id) {
				this.id = e.id;
			} else {
				uni.showModal({
					title: '提示',
					content: '参数无效'
				})
				return;
			}
			this.getLink();
		},
		methods: {
			getLink() {
				this.$request({
					url: "/api/mall/outside-link",
					data: {
						id: this.id
					}
				}).then(e => {
					if (e.code == 0) {
						this.url = e.data.link.link;
					}
				})
			}

		}
	}
</script>

<style>

</style>
