<template>
	<view style="background-color: #FFFFFF;height: 100%;width: 100%; top: 0;left: 0;position: absolute;">

		<tui-rich-text :value="about"></tui-rich-text>
		<tui-loadmore v-if="loading" :index="3" type="red"></tui-loadmore>
		<tui-nomore v-if="is_no_more" backgroundColor="#f5f5f5"></tui-nomore>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				about: '',
				loading: false,
				is_no_more: false,
			}
		},
		onLoad: function(e) {
			this.getAbout();
		},
		methods: {
			getAbout() {
				this.loading = true;
				this.$request({
					url: "/api/mall/about"
				}).then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.about = res.data.about
						if (!this.about) {
							this.is_no_more = true;

						}
					}

				})
			},

		}
	}
</script>

<style>

</style>
