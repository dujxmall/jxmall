<template>
	<tui-page class="container">
		<tui-diy-view :value="pageData"></tui-diy-view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				id: '',
				pageData: '',

			}
		},
		onLoad: function(e) {
			if (e.id) {
				this.id = e.id;
			} else {
				uni.showModal({
					title: '提示',
					content: '参数不正确！'
				})
				return;
			}
			this.$request({
				url: "/api/index/diy-page",
				data: {
					id: this.id
				}
			}).then(res => {
				if (res.code == 0) {
					 uni.setNavigationBarTitle({
					 	title:res.data.name
					 })
					this.pageData = res.data.page_data
				}
			})
		},
		methods: {

		}
	}
</script>

<style>

</style>
