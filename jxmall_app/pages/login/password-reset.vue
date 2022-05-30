<template>
	<view>
		<view style="height: 100vh;width: 100%;background-color: #FFFFFF;">
			<view style="width: 75%;margin: 0 auto;">
				<view class="flex-y-center flex-x-center" style="padding: 50rpx;padding-top:40%">
					<image v-if="!setting" src="../../static/logo.png" mode="aspectFill"
						style="width: 150rpx;height: 150rpx;border-radius: 10rpx;overflow: hidden;"></image>
					<image v-if="setting" :src="setting.login_pic" mode="aspectFill"
						style="width: 150rpx;height: 150rpx;border-radius: 10rpx;overflow: hidden;"></image>
				</view>
				<input v-model="form.mobile" placeholder="请输入手机号" maxlength="11" type="text" class="input-item"
					style="margin-top: 50rpx;">
				<input placeholder="输入新密码" v-model="form.password" type="password" class="input-item"
					style="margin-top: 50rpx;">
				<view class="input-item flex-y-center" style="margin-top: 50rpx;">
					<input placeholder="输入验证码" maxlength="6" v-model="form.code" type="number" style="width: 70%;">
					<view style="width: 30%;">
						<view style="width: 150rpx;height: 60rpx;font-size: 9pt;border-left: solid #B3B3B3 1rpx;"
							class="flex-y-center flex-x-center" v-if="!is_send_code" @click="getCode">{{msg}}</view>
						<view style="width: 150rpx;height: 60rpx;font-size: 9pt;border-left: solid #B3B3B3 1rpx;"
							class="flex-y-center flex-x-center" v-if="is_send_code">{{sec}}秒后重试</view>
					</view>
				</view>
				<view class="flex-y-center flex-x-center" style="padding-top: 70rpx;">
					<tui-button shape="circle" type="danger" width="550rpx" height="80rpx" @click="submit">确定修改
					</tui-button>
				</view>
				<view style="color: #B2B2B2;font-size: 9pt;padding-top: 40rpx;text-align: center;" @click="$T.back(1)">
					 点此登录
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				is_loading: false,
				msg: '获取验证码',
				sec: 180,
				is_send_code: false,
				setting: null,
				is_wechat: false,
				form: {
					mobile: '',
					password: '',
					code: '',
				}
			};
		},
		onLoad(options) {
			let setting = uni.getStorageSync('MALL_SETTING');
			if (setting) {
				this.setting = setting;
			}
		},
		methods: {
			getCode() {
				this.is_send_code = true;
				if (this.is_loading) {
					return;
				}
				this.is_loading = true;
				this.$request({
					url: "/api/login/sms-code",
					data: this.form,
					method: 'post'
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						var time = setInterval(() => {
							this.sec -= 1;
							if (this.sec <= 0) {
								this.is_send_code = false;
								this.sec = 180;
								clearInterval(time);
							}
						}, 1000)
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: (e) => {

							}
						})
					} else {
						this.is_send_code = false;
						uni.showModal({
							title: '提示',
							content: res.msg,
							showCancel: false
						})
					}
				})
			},
			submit() {
				this.$request({
					url: "/api/login/password-reset",
					data: this.form,
					method: 'post'
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: (e) => {
								uni.redirectTo({
									url: "/pages/login/login"
								})
							}
						})
					} else {
						this.is_loading = false;
						uni.showModal({
							title: '提示',
							content: res.msg,
							showCancel: false
						})
					}
				})
			} 
		}
	};
</script>
<style lang="scss" scoped>
	.bg-danger-color {
		background-color: $uni-color-danger;
	}

	.input-item {
		height: 80rpx;
		border: solid #f0f0f0 1rpx;
		width: 550rpx;
		border-radius: 80rpx;
		background-color: #F0F0F0;
		padding-left: 40rpx;
	}
</style>
