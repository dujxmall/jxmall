<template>
	<tui-page>
		<view v-if="user" style="padding-top: 20rpx;background-color: #FFFFFF;padding-bottom: 30rpx;">
			<view style="text-align: center;">
				<image :src="user.avatar_url" style="width: 100rpx;height: 100rpx;border-radius: 50%;"></image>
				<view style="font-weight: bold;">
					我的余额：<text style="color: #FF0000;">{{user.balance}}</text>
				</view>

				<view style="margin-top: 30rpx; text-align: center;">
					<view style="border-bottom:solid #f0f0f0 1rpx;width: 60%;margin: 0 auto;">
						<input type="text" placeholder="请输入对方账户ID" v-model="target_id">
					</view>
					<view style="width: 100%;text-align: center;margin-top: 30rpx;" v-if="!target">
						<tui-button @click="getTargetInfo" margin="0 auto" shape="circle" width="200rpx" height="70rpx"
							type="danger" color="#fff">搜索用户
						</tui-button>
					</view>
				</view>

				<view style="margin-top: 30rpx; text-align: center;" v-if="target">
					<image :src="target.avatar_url" style="width: 100rpx;height: 100rpx;border-radius: 50%;"></image>
					<view style="font-weight: bold;">
						昵称：<text style="color: #FF0000;">{{target.nickname}}</text>
					</view>
					<view style="border-bottom:solid #f0f0f0 1rpx;width: 60%;margin: 0 auto;padding-top: 40rpx;">
						<input type="text" placeholder="输入转赠金额" v-model="money">
					</view>
					<view class="flex-y-center flex-x-between"
						style="text-align: center;margin: 0 auto;width: 54%;padding-top: 30rpx;">
						<tui-button @click="changeTarget" margin="0 auto" shape="circle" width="200rpx" height="70rpx"
							type="warning" color="#fff">换个用户
						</tui-button>
						<tui-button @click="confirm" margin="0 auto" shape="circle" width="200rpx" height="70rpx"
							type="danger" color="#fff">确定转赠
						</tui-button>
					</view>
				</view>

			</view>

		</view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {

				user: null,
				target_id: '',
				target: null,
				money: '',

			}
		},
		onLoad: function(e) {
			this.getInfo();
		},
		methods: {
			changeTarget() {
				this.target = null;
				this.target_id = '';
			},

			getTargetInfo() {
				this.$request({
					url: "/api/finance/target-info",
					data: {
						target_id: this.target_id
					}
				}).then(res => {
					if (res.code == 0) {
						this.target = res.data.user;
						console.log(res);
					} else {
						uni.showModal({
							title: '提示',
							content: res.msg
						})
					}

				})

			},
			confirm() {
				uni.showLoading({
					title: '正在提交'
				})
				this.$request({
					url: "/api/finance/balance-transfer",
					data: {
						money: this.money,
						target_id: this.target_id,
					},
					method: 'post'
				}).then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						this.getInfo();
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: (e) => {
								this.target = null;
								this.target_id = '';
							}
						})
					}
					if (res.code == 1) {
						uni.showModal({
							title: '提示',
							content: res.msg
						})
					}
				})
			},
			getInfo() {
				this.$request({
					url: "/api/user/index",
				}).then(res => {
					if (res.code == 0) {
						uni.setStorageSync('USER', res.data.user)
						this.user = res.data.user;
					}
					if (res.code == -1) {
						this.is_no_login = true;
					}
				})

			},
		}
	}
</script>

<style>

</style>
