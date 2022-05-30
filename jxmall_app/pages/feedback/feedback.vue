<template>
	<tui-page ref="page">
		<view style="height: 100vh;width: 100%;background-color: #FFFFFF;">
			<view style="width: 80%;margin: 0 auto;text-align: center;padding-top: 20rpx;">
				<textarea v-model="content" placeholder="您的意见对我们很重要"
					style="border: solid #f0f0f0 1rpx;padding:20rpx;border-radius: 20rpx;margin: 0 auto;box-sizing: border-box;text-align: start;height: 450rpx;" />
				<view style="margin-top: 30rpx;">
					<tui-button shape="circle" type="danger" height="70rpx" @click="submit">提交</tui-button>
				</view>
			</view>
		</view>
	</tui-page>
</template>
<script>
	export default {
		data() {
			return {
				content: '',
				is_loading: false,
			}
		},
		methods: {
			submit() {
				if (!this.content) {
					uni.showModal({
						title: '提示',
						content: '反馈内容不能为空',
						success: (e) => {

						}
					})
					return
				}
				this.is_loading = true;
				this.$request({
					url: "/api/user/feedback",
					data: {
						content: this.content,
					},
					method: 'post'
				}).then(res => {
					this.is_loading = false;
					if (res.code == -1) {
						this.$refs.page.showLoginModalWindow();
						return;
					}
					if (res.code == 0) {
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: (e) => {
								uni.navigateBack({
									delta: 1
								})
							}
						})
					} else {
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: (e) => {

							}
						})
					}
				})
			},
		}
	}
</script>

<style>

</style>
