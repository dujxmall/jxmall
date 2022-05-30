<template>
	<tui-page>
		<view class="tui-set-box" v-if="user">
			<tui-list-cell padding="0" :lineLeft="false" :arrow="true">
				<view class="tui-list-cell tui-info-box">
					<image :src="user.avatar_url" class="tui-avatar"></image>
					<view style="font-size: 10pt;">
						<view>ID:{{user.id}}</view>
						<view>
							{{user.nickname}}
						</view>
					</view>
				</view>
			</tui-list-cell>
			<tui-list-cell padding="0" :lineLeft="false" :arrow="true" @click="pageTo('/pages/address/address')">
				<view class="tui-list-cell">
					地址管理
				</view>
			</tui-list-cell>
			<view class="tui-mtop">
				<tui-list-cell padding="0" :lineLeft="false" :arrow="true">
					<view class="tui-list-cell">
						手机号：{{user.mobile}}
					</view>
				</tui-list-cell>
			</view>
			<view class="tui-mtop">
				<tui-list-cell padding="0" :lineLeft="false" :arrow="false">
					<view class="tui-list-cell" v-if="!user.birthday">
						<uni-datetime-picker type="date" v-model="user.birthday" @change="birthdayChange">
							生日：{{user.birthday?user.birthday:'请选择'}}</uni-datetime-picker>
					</view>
					<view class="tui-list-cell" v-if="user.birthday">
						生日：{{user.birthday}}
					</view>
					<tui-icon size="25" name="arrowright"></tui-icon>
				</tui-list-cell>
			</view>
			<view class="tui-mtop">
				<tui-list-cell padding="0" :lineLeft="false" :arrow="false" @click="pageTo('/pages/about/about')">
					<view class="tui-list-cell">
						关于我们
					</view>
					<tui-icon size="25" name="arrowright"></tui-icon>
				</tui-list-cell>
				<tui-list-cell padding="0" :lineLeft="false" :arrow="false" @click="$T.go('/pages/feedback/feedback')">
					<view class="tui-list-cell">
						意见反馈
					</view>
					<tui-icon size="25" name="arrowright"></tui-icon>
				</tui-list-cell>
				<tui-list-cell padding="0" :lineLeft="false" :arrow="false" @click="clearStorage">
					<view class="tui-list-cell">
						清理缓存
					</view>
					<tui-icon size="25" name="arrowright"></tui-icon>
				</tui-list-cell>
			</view>

			<view class="tui-exit">
				<tui-button shape="circle" shadow type="danger" height="88rpx" @click="logout">退出登录</tui-button>
			</view>
		</view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {

				user: null
			}
		},
		onLoad: function(e) {

			this.$request({
				url: "/api/user/index",
			}).then(res => {
				if (res.code == 0) {
					uni.setStorageSync('USER', res.data.user)
					this.user = res.data.user;
				}
			})


		},
		methods: {
			clearStorage() {
				uni.showModal({
					title: '提示',
					content: '确定清理缓存',
					success: (e) => {
						if (e.confirm) {
							let mall_id=uni.getStorageSync('X-MALL-ID');
							uni.clearStorageSync();
							if(mall_id){
								uni.setStorageSync('X-MALL-ID',mall_id);
							}
							uni.reLaunch({
								url: "/pages/index/index"
							})
						}
					}
				})
			},
			birthdayChange(e) {
				 
				uni.showModal({
					title: '提示',
					content: '将生日设为：' + e + "，设置后将无法更改",
					success: (e) => {
						if (e.confirm) {
							this.$request({
								url: "/api/user/set-birthday",
								data: {
									birthday: this.user.birthday,
								},
								method: 'post'
							}).then(res => {
								if (res.code == 0) {
									uni.showModal({
										title: '提示',
										content: res.msg
									})
								}
							})
						}
					}
				})
			},
			pageTo(page) {
				uni.navigateTo({
					url: page
				})
			},
			logout() {
				let self = this;
				uni.showModal({
					title: '提示',
					content: "确定退出登录",
					success: function(e) {
						if (e.confirm) {
							self.$request({
								url: '/api/user/logout'
							}).then(res => {
								if (res.code == 0) {
									uni.removeStorageSync('USER');
									uni.removeStorageSync('SEARCH_KEYWORD');
									uni.removeStorageSync('ACCESS_TOKEN');
									uni.removeStorageSync('ORDER_SUBMIT_DATA');
									uni.removeStorageSync('WECHAT_USER_INFO');
									uni.reLaunch({
										url: "/pages/index/index"
									})
								}
							})
						}
					}
				})
			},
			href(page) {
				let url = "";
				switch (page) {
					case 1:
						url = "../userInfo/userInfo"
						break;
					case 2:
						url = "../address/address"
						break;
					case 3:
						url = "../notice/notice"
						break;
					case 4:
						url = "/pages/common/about/about"
						break;
					case 5:
						url = "/pages/my/feedback/feedback?page=mall"
						break;
					default:
						break;
				}
				uni.navigateTo({
					url: url
				})
			}
		}
	}
</script>

<style>
	.tui-set-box {
		padding-bottom: 20rpx;
		color: #333;
	}

	.tui-list-cell {
		display: flex;
		align-items: center;
		padding: 24rpx 30rpx;
		font-size: 30rpx;
	}

	.tui-info-box {
		font-size: 34rpx;
	}

	.tui-avatar {
		width: 140rpx;
		height: 140rpx;
		border-radius: 50%;
		margin-right: 20rpx;
	}

	.tui-mtop {
		margin-top: 20rpx;
	}

	.tui-exit {
		padding: 100rpx 24rpx;
	}
</style>
