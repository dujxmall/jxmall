<template>
	<tui-page ref="page">
		<view style="background-color: #FFFFFF;padding-top: 30rpx;">
			<view v-if="article.video!=''">
				<video :src="article.video" style="width: 100%;" controls></video>
			</view>
			<view style="font-weight: bold;font-size: 12pt;padding: 0 20rpx;">{{article.title}}</view>
			<view style="padding: 0 20rpx;color: #444444;font-size: 9pt;height: 100rpx;"
				class="flex-y-center flex-x-between">
				<view>发布时间：{{article.created_at}}</view>
				<view>阅读量：{{article.views}}</view>
			</view>
			<view style="padding-top: 20rpx;">
				<tui-rich-text :value="article.detail"></tui-rich-text>
			</view>
		</view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				id: '',
				pid: '',
				article: {
					created_at: '',
					title: '',
					detail: '',
					video: '',
				}
			}
		},
		onLoad: function(options) {
			if (options.id) {
				this.id = options.id;
			}
			if (options.pid) {
				this.pid = options.pid
			}
		},
		onShow: function() {
			this.getArticle();
		},
		onShareAppMessage: function(e) {
			let user = uni.getStorageSync('USER');
			if (user) {
				return {
					path: 'plugins/article/article?pid=' + user.id + "&id=" + this.id,
				}
			}
			return {
				path: 'plugins/article/article?id=' + this.id,
			}
		},
		methods: {
			getArticle() {
				this.$request({
					url: "/plugin/article/api/article/article",
					data: {
						id: this.id,
						pid: this.pid
					}
				}).then(res => {
					console.log(res);
					if (res.code == 0) {
						uni.setNavigationBarTitle({
							title: res.data.article.title
						})
						this.article = res.data.article;
					}
					if (res.code == -1) {
						this.$refs.page.showLoginModalWindow();
					}
				})
			}

		}
	}
</script>

<style>

</style>
