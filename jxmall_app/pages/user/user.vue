<template>
	<tui-page ref="page">
		<view>
			<!--header-->
			<tui-navigation-bar splitLine @init="initNavigation" @change="opacityChange" :scrollTop="scrollTop"
				title="我的" backgroundColor="#fff" color="#333">
				<view class="tui-header-box" :style="{marginTop:top+'px'}">
					<!--个人中心页为主页面，不应有返回键-->
					<!-- #ifndef MP -->
					<view class="tui-header-icon">
						<!-- 	<view class="tui-icon-box tui-icon-message"  @tap="href(7)">
							<tui-icon name="message" color="#333" :size="26"></tui-icon>
							<view class="tui-badge tui-badge-red">1</view>
						</view> -->

						<view></view>
						<view class="tui-icon-box tui-icon-setup" @tap="pageTo('/pages/setting/setting')">
							<tui-icon name="setup" color="#333" :size="26">
							</tui-icon>
						</view>
					</view>
					<!-- #endif -->
				</view>
			</tui-navigation-bar>
			<!--header-->
			<view class="tui-mybg-box">
				<!-- 
				未登录的头像
				 	<image :src="/static/images/my/mine_def_touxiang_3x.png" class="tui-avatar" @tap="href(3)"></image>-->
				<image :src="page_setting.header.user_bg" class="tui-my-bg" mode="widthFix"
					v-if="page_setting&&page_setting.header"></image>
				<view class="tui-header-center" style="padding-top: 40rpx;" v-if="user&&!is_no_login">
					<image :src="user.avatar_url" class="tui-avatar" @click="pageTo('/pages/setting/setting')"></image>
					<view class="tui-info">
						<view class="tui-nickname">
							<view style="padding-top: 20rpx;">
								{{user.nickname?user.nickname:'未登录~'}}
								<image v-if="user&&user.level!=0" :src="user.level_icon" class="tui-img-vip"></image>
							</view>
						</view>
						<view style="font-size: 8pt;color: #FFFFFF;">
							{{user.level_name}}
						</view>
					</view>
				</view>
				<view class="tui-header-center" style="padding-top: 40rpx;" v-if="user&&is_no_login">
					<image :src="user.avatar_url" class="tui-avatar" @tap="showLogin"></image>
					<view class="tui-info" @tap="showLogin">
						<view class="tui-nickname">
							<view>
								{{user.nickname?user.nickname:'未登录~'}}
								<image v-if="user&&user.level!=0&&user.level_icon" :src="user.level_icon"
									class="tui-img-vip"></image>
							</view>
						</view>
						<view style="font-size: 8pt;color: #FFFFFF;">
							{{user.level_name}}
						</view>
					</view>
				</view>
			</view>
			<view class="tui-content-box" v-if="page_setting">
				<view class="tui-box tui-order-box">
					<tui-list-cell :arrow="true" padding="0" :lineLeft="false" @click="href(4)">
						<view class="tui-cell-header">
							<view class="tui-cell-title">我的订单</view>
							<view class="tui-cell-sub" @click.stop="pageToOrder(-1)">查看全部订单</view>
						</view>
					</tui-list-cell>
					<view class="tui-order-list">
						<view class="tui-order-item" @tap.stop="pageToOrder(0)">
							<view class="tui-icon-box">
								<image :src="page_setting.order.status_0.icon" class="tui-order-icon"></image>
								<view class="tui-badge tui-badge-red" v-if="page_setting.order.status_0.num>0">
									{{page_setting.order.status_0.num}}
								</view>
							</view>
							<view class="tui-order-text">{{page_setting.order.status_0.name}}</view>
						</view>
						<view class="tui-order-item" @tap.stop="pageToOrder(1)">
							<view class="tui-icon-box">
								<image :src="page_setting.order.status_1.icon" class="tui-order-icon"></image>
								<view class="tui-badge tui-badge-red" v-if="page_setting.order.status_1.num>0">
									{{page_setting.order.status_1.num}}
								</view>
							</view>
							<view class="tui-order-text">{{page_setting.order.status_1.name}}</view>
						</view>
						<view class="tui-order-item" @tap.stop="pageToOrder(2)">
							<view class="tui-icon-box">
								<image :src="page_setting.order.status_2.icon" class="tui-order-icon"></image>
							</view>
							<view class="tui-order-text">{{page_setting.order.status_2.name}}</view>
						</view>
						<view class="tui-order-item" @tap.stop="pageToOrder(3)">
							<view class="tui-icon-box">
								<image :src="page_setting.order.status_3.icon" class="tui-order-icon"></image>
								<view class="tui-badge tui-badge-red" v-if="page_setting.order.status_3.num>0">
									{{page_setting.order.status_3.num}}
								</view>
							</view>
							<view class="tui-order-text">{{page_setting.order.status_3.name}}</view>
						</view>
						<view class="tui-order-item" @tap.stop="pageToOrder(4)">
							<view class="tui-icon-box">
								<image :src="page_setting.order.status_4.icon" class="tui-order-icon"></image>
								<view class="tui-badge tui-badge-red" v-if="page_setting.order.status_4.num>0">
									{{page_setting.order.status_4.num}}
								</view>
							</view>
							<view class="tui-order-text">{{page_setting.order.status_4.name}}</view>
						</view>
					</view>
				</view>

				<view class="tui-box tui-assets-box" v-if="page_setting&&is_no_login&&page_setting.finance">
					<tui-list-cell padding="0" unlined :hover="false">
						<view class="tui-cell-header">
							<view class="tui-cell-title">{{page_setting.finance.name}}</view>
						</view>
					</tui-list-cell>
					<view class="tui-order-list tui-assets-list">
						<view class="tui-order-item" v-if="page_setting.finance.integral.is_show==1">
							<view class="tui-assets-num">
								<text>0</text>
							</view>
							<view class="tui-assets-text">{{page_setting.finance.integral.name}}</view>
						</view>
						<view class="tui-order-item" v-if="page_setting.finance.balance.is_show==1">
							<view class="tui-assets-num">
								<text>0.00</text>
							</view>
							<view class="tui-assets-text">{{page_setting.finance.balance.name}}</view>
						</view>
						<view class="tui-order-item" v-if="page_setting.finance.price.is_show==1">
							<view class="tui-assets-num">
								<text>0.00</text>
							</view>
							<view class="tui-assets-text"> <text>{{page_setting.finance.price.name}}</text></view>
						</view>
						<view class="tui-order-item" @tap="$T.go('/pages/coupon/coupon?status=1')"
							v-if="page_setting.finance.coupon.is_show==1">
							<view class="tui-assets-num">
								<text>0</text>
							</view>
							<view class="tui-assets-text"> <text>{{page_setting.finance.coupon.name}}</text></view>
						</view>
					</view>
				</view>
				<view class="tui-box tui-assets-box" v-if="!is_no_login&&page_setting&&page_setting.finance">
					<tui-list-cell padding="0" unlined :hover="false">
						<view class="tui-cell-header">
							<view class="tui-cell-title">{{page_setting.finance.name}}</view>
						</view>
					</tui-list-cell>
					<view class="tui-order-list tui-assets-list">
						<view class="tui-order-item" v-if="page_setting.finance.integral.is_show==1"
							@click="navTo('/pages/integral-log/integral-log')">
							<view class="tui-assets-num">
								<text>{{user.integral}}</text>
							</view>
							<view class="tui-assets-text">{{page_setting.finance.integral.name}}</view>
						</view>
						<view class="tui-order-item" v-if="page_setting.finance.balance.is_show==1"
							@click="navTo('/pages/balance-log/balance-log')">
							<view class="tui-assets-num">
								<text>{{user.balance}}</text>
							</view>
							<view class="tui-assets-text">{{page_setting.finance.balance.name}}</view>
						</view>
						<view class="tui-order-item" v-if="page_setting.finance.price.is_show==1"
							@click="navTo('/pages/price-log/price-log')">
							<view class="tui-assets-num">
								<text>{{user.price}}</text>
							</view>
							<view class="tui-assets-text"> <text>{{page_setting.finance.price.name}}</text></view>
						</view>
						<view class="tui-order-item" @tap="navTo('/pages/coupon/coupon?status=1')"
							v-if="page_setting.finance.coupon.is_show==1">
							<view class="tui-assets-num">
								<text>{{user.coupon}}</text>
							</view>
							<view class="tui-assets-text"> <text>{{page_setting.finance.coupon.name}}</text></view>
						</view>
					</view>
				</view>



				<view class="tui-box tui-tool-box" v-if="page_setting">
					<tui-list-cell :arrow="true" padding="0" :lineLeft="false" v-if="page_setting.menus.type==0">
						<view class="tui-cell-header">
							<view class="tui-cell-title">{{page_setting.menus.name}}</view>
						</view>
					</tui-list-cell>
					<view class="tui-order-list tui-flex-wrap" style="justify-content: start;"
						v-if="page_setting.menus.type==0">
						<view class="tui-tool-item" v-if="page_setting.menus.list.length"
							v-for="(item,index) in  page_setting.menus.list" @click="navTo(item.url)">
							<view class="tui-icon-box">
								<image :src="item.icon" class="tui-tool-icon"></image>
							</view>
							<view class="tui-tool-text">{{item.name}}</view>
						</view>
						<view style="text-align: center;" v-if="page_setting.menus.list.length==0">
							暂无菜单
						</view>
					</view>
					<view style="justify-content: start;" v-if="page_setting.menus.type==1">
						<view style="padding: 10rpx 10rpx;border-bottom: solid #f0f0f0 1rpx;"
							class="flex-row flex-x-between" v-if="page_setting.menus.list.length"
							v-for="(item,index) in  page_setting.menus.list" @click="navTo(item.url)">
							<view class="flex-row flex-y-center" style="box-sizing: border-box;">
								<view class="tui-icon-box">
									<image :src="item.icon" class="tui-tool-icon"></image>
								</view>
								<view class="tui-tool-text" style="font-size: 11pt;">{{item.name}}</view>
							</view>
							<tui-icon name="arrowright" size="25" color="#c0c0c0"></tui-icon>
						</view>
						<view style="text-align: center;" v-if="page_setting.menus.list.length==0">
							暂无菜单
						</view>
					</view>
				</view>
				<view class="tui-box tui-tool-box"
					v-if="page_setting&&page_setting.menus2&&page_setting.menus2.is_show==1">
					<tui-list-cell :arrow="true" padding="0" :lineLeft="false" v-if="page_setting.menus2.type==0">
						<view class="tui-cell-header">
							<view class="tui-cell-title">{{page_setting.menus2.name}}</view>
						</view>
					</tui-list-cell>
					<view class="tui-order-list tui-flex-wrap" style="justify-content: start;"
						v-if="page_setting.menus.type==0">
						<view class="tui-tool-item" v-if="page_setting.menus2.list.length"
							v-for="(item,index) in  page_setting.menus2.list" @click="navTo(item.url)">
							<view class="tui-icon-box">
								<image :src="item.icon" class="tui-tool-icon"></image>
							</view>
							<view class="tui-tool-text">{{item.name}}</view>
						</view>
						<view style="text-align: center;" v-if="page_setting.menus2.list.length==0">
							暂无菜单
						</view>
					</view>
					<view style="justify-content: start;" v-if="page_setting.menus2.type==1">
						<view style="padding: 10rpx 10rpx;border-bottom: solid #f0f0f0 1rpx;"
							class="flex-row flex-x-between" v-if="page_setting.menus2.list.length"
							v-for="(item,index) in  page_setting.menus2.list" @click="navTo(item.url)">
							<view class="flex-row flex-y-center" style="box-sizing: border-box;">
								<view class="tui-icon-box">
									<image :src="item.icon" class="tui-tool-icon"></image>
								</view>
								<view class="tui-tool-text" style="font-size: 11pt;">{{item.name}}</view>
							</view>
							<tui-icon name="arrowright" size="25" color="#c0c0c0"></tui-icon>
						</view>
						<view style="text-align: center;" v-if="page_setting.menus2.list.length==0">
							暂无菜单
						</view>
					</view>
				</view>


				<!--为你推荐-->
				<tui-divider :size="28" :bold="true" color="#333" width="50%" :backgroundColor="''" v-if="list.length>0"
					style="margin: 20rpx 0;">为你推荐</tui-divider>
				<view class="tui-product-list" v-if="list.length>0">
					<view class="tui-product-container">
						<uni-grid :column="2" :square="false" :showBorder="false" :highlight="false">
							<block v-for="(item,index) in list" :key="index">
								<!--商品列表-->
								<uni-grid-item>
									<view style="padding: 0 2rpx;box-sizing:border-box">
										<tui-goods-item :goods="item"></tui-goods-item>
									</view>
								</uni-grid-item>
								<!--商品列表-->
							</block>
						</uni-grid>
					</view>

				</view>
				<!-- <view class="tui-btn-back" @tap="back">返回</view> -->
				<!--加载loadding-->
				<tui-loadmore v-if="loadding" :index="3" type="red"></tui-loadmore>
			</view>
		</view>
	</tui-page>
</template>

<script>
	export default {
		onLoad: function(options) {
			let obj = {};
			// #ifdef MP-WEIXIN
			obj = wx.getMenuButtonBoundingClientRect();
			// #endif
			// #ifdef MP-BAIDU
			obj = swan.getMenuButtonBoundingClientRect();
			// #endif
			// #ifdef MP-ALIPAY
			my.hideAddToDesktopMenu();
			// #endif
			uni.getSystemInfo({
				success: (res) => {
					this.width = obj.left || res.windowWidth;
					this.height = obj.top ? (obj.top + obj.height + 8) : (res.statusBarHeight + 44);
					this.top = obj.top ? (obj.top + (obj.height - 32) / 2) : (res.statusBarHeight + 6);
					this.scrollH = res.windowWidth * 0.6
				}
			})
		},
		data() {
			return {

				top: 0, //标题图标距离顶部距离
				opacity: 0,
				scrollTop: 0.5,
				page_setting: null,
				user: {
					avatar_url: '/static/default/header.png',
					nickname: '未登录~',
					level_name: '游客'
				},
				page: 1,
				is_no_login: false,
				list: [],
				is_no_more: false,
				pageIndex: 1,
				loadding: false,
				pullUpOn: true
			}
		},
		onLoad() {
			let user = uni.getStorageSync('USER');
			if (user) {
				this.user = user;
			}
			let page_data = uni.getStorageSync('USER_CENTER_SETTING');
			if (page_data) {
				this.page_setting = page_data;
			}
			let recommendGoods = uni.getStorageSync('RECOMMEND_GOODS');
			if (recommendGoods) {
				this.list = recommendGoods;
			}
		},
		onShow: function() {

			this.is_no_more = false;
			this.getData();
			this.getRecommend();
			this.getPageData();
		},
		methods: {
			showLogin() {
				this.$refs.page.showLoginModalWindow();
			},
			getPageData() {
				this.$request({
					url: "/api/user/page-data",
				}).then(res => {
					if (res.code == 0) {
						uni.setStorageSync('USER_CENTER_SETTING', res.data.page_data)
						this.page_setting = res.data.page_data;

					}
				})
			},
			getData() {
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
			pageToOrder(status) {
				if (this.is_no_login) {
					this.showLogin();
					return;
				}
				let url = "/pages/order/order?status=" + status;
				uni.navigateTo({
					url: url
				})
			},
			navTo(url) {
				if (this.is_no_login) {
					this.showLogin();
					return;
				}
				uni.navigateTo({
					url: url
				})
			},
			pageTo(page) {
				if (this.is_no_login) {
					this.showLogin();
					return;
				}
				uni.navigateTo({
					url: page
				})
			},
			href(page) {
				if (this.is_no_login) {
					this.showLogin();
					return;
				}
				let url = "";

				if (url) {
					uni.navigateTo({
						url: page
					})
				} else {
					this.tui.toast("功能尚未完善~")
				}
			},
			detail: function(e) {
				uni.navigateTo({
					url: '/pages/goods/goods?id=' + e.id
				})
			},
			initNavigation(e) {
				this.opacity = e.opacity;
				this.top = e.top;
			},
			opacityChange(e) {
				this.opacity = e.opacity;
			},
			back() {
				uni.navigateBack();
			},
			getRecommend() {

				if (this.is_no_more) {
					uni.showToast({
						title: '没有更多数据'
					})
					return;
				}
				this.$request({
					url: '/api/goods/list',
					data: {
						is_recommend: 1,
						page: this.page
					}
				}).then(res => {

					if (res.code == 0) {
						if (this.page == 1) {
							this.list = res.data.list
						} else {
							this.list = this.list.concat(res.data.list)
						}
						if (res.data.pagination.page_count > this.page) {
							this.page++;
						} else {
							this.is_no_more = true;
						}

						uni.setStorageSync('RECOMMEND_GOODS', this.list);
					}

				})
			},
		},
		onPageScroll(e) {
			this.scrollTop = e.scrollTop;
		},
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh()
			}, 200)
		},
		onReachBottom: function() {
			if (!this.pullUpOn) return;
			this.loadding = true;
			if (this.is_no_more) {
				this.loadding = false;
				this.pullUpOn = false
			} else {
				this.getRecommend();
				this.loadding = false
			}
		}
	}
</script>

<style>
	.tui-header-box {
		width: 100%;
		padding: 0 30rpx 0 20rpx;
		box-sizing: border-box;
		position: fixed;
		top: 0;
		left: 0;
		display: flex;
		align-items: center;
		justify-content: flex-end;
		height: 32px;
		transform: translateZ(0);
		z-index: 9999;
	}

	/* #ifndef MP */
	.tui-header-icon {
		min-width: 120rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	/* #endif */
	/* #ifdef MP */
	.tui-set-box {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	/* #endif */
	.tui-icon-box {
		position: relative;
	}

	.tui-icon-setup {
		margin-left: 8rpx;
	}

	.tui-badge {
		position: absolute;
		font-size: 24rpx;
		height: 32rpx;
		min-width: 20rpx;
		padding: 0 6rpx;
		border-radius: 40rpx;
		right: 10rpx;
		top: -5rpx;
		transform: scale(0.8) translateX(60%);
		transform-origin: center center;
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 10;
	}

	.tui-badge-red {
		background: #F74D54;
		color: #fff;
	}

	.tui-badge-white {
		background: #fff;
		color: #F74D54;
	}

	.tui-badge-dot {
		position: absolute;
		height: 12rpx;
		width: 12rpx;
		border-radius: 50%;
		right: -12rpx;
		top: 0;
		background: #F74D54;
	}

	.tui-mybg-box {
		width: 100%;
		height: 464rpx;
		position: relative;
	}

	.tui-my-bg {
		width: 100%;
		height: 464rpx;
		display: block;
	}

	.tui-header-center {
		position: absolute;
		width: 100%;
		height: 128rpx;
		left: 0;
		top: 120rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
	}

	.tui-avatar {
		flex-shrink: 0;
		width: 128rpx;
		height: 128rpx;
		display: block;
		border-radius: 50%;
	}

	.tui-info {
		width: 60%;
		padding-left: 30rpx;

	}

	.tui-nickname {
		font-size: 30rpx;
		font-weight: 500;
		color: #fff;
		display: flex;
		align-items: center;
	}

	.tui-img-vip {
		width: 56rpx;
		height: 24rpx;
		margin-left: 18rpx;
	}

	.tui-explain {
		width: 80%;
		font-size: 24rpx;
		font-weight: 400;
		color: #fff;
		opacity: 0.75;
		padding-top: 8rpx;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.tui-btn-edit {
		flex-shrink: 0;
		padding-right: 22rpx;
	}

	.tui-header-btm {
		width: 100%;
		padding: 0 30rpx;
		box-sizing: border-box;
		position: absolute;
		left: 0;
		top: 280rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		color: #fff;
	}

	.tui-btm-item {
		flex: 1;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
	}

	.tui-btm-num {
		font-size: 32rpx;
		font-weight: 600;
		position: relative;
	}

	.tui-btm-text {
		font-size: 24rpx;
		opacity: 0.85;
		padding-top: 4rpx;
	}

	.tui-content-box {
		width: 100%;
		padding: 0 30rpx;
		box-sizing: border-box;
		position: relative;
		top: -72rpx;
		z-index: 10;
	}

	.tui-box {
		width: 100%;
		background: #fff;
		box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.1);
		border-radius: 10rpx;
		overflow: hidden;
	}

	.tui-order-box {
		height: 208rpx;
	}

	.tui-cell-header {
		width: 100%;
		height: 74rpx;
		padding: 0 26rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.tui-cell-title {
		font-size: 30rpx;
		line-height: 30rpx;
		font-weight: 600;
		color: #333;
	}

	.tui-cell-sub {
		font-size: 26rpx;
		font-weight: 400;
		color: #999;
		padding-right: 28rpx;
	}

	.tui-order-list {
		width: 100%;
		height: 134rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;

	}

	.tui-order-item {
		flex: 1;
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.tui-order-text,
	.tui-tool-text {
		font-size: 26rpx;
		font-weight: 400;
		color: #666;
		padding-top: 4rpx;
	}

	.tui-tool-text {
		font-size: 24rpx;
	}

	.tui-order-icon {
		width: 56rpx;
		height: 56rpx;
		display: block;
	}

	.tui-assets-box {
		height: 178rpx;
		margin-top: 20rpx;
	}

	.tui-assets-list {
		height: 84rpx;
	}

	.tui-assets-num {
		font-size: 32rpx;
		font-weight: 500;
		color: #333;
		position: relative;
	}

	.tui-assets-text {
		font-size: 24rpx;
		font-weight: 400;
		color: #666;
		padding-top: 6rpx;
	}

	.tui-tool-box {
		margin-top: 20rpx;
	}

	.tui-flex-wrap {
		flex-wrap: wrap;
		height: auto;
		padding-bottom: 30rpx;
	}

	.tui-tool-item {
		width: 25%;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		padding-top: 30rpx;
	}

	.tui-tool-icon {
		width: 64rpx;
		height: 64rpx;
		display: block;
	}

	.tui-badge-icon {
		width: 66rpx;
		height: 30rpx;
		position: absolute;
		right: 0;
		transform: translateX(88%);
		top: -15rpx;
	}

	/*为你推荐*/
	.tui-product-list {
		display: flex;
		justify-content: space-between;
		flex-direction: row;
		flex-wrap: wrap;
		box-sizing: border-box;
	}

	.tui-product-container {
		flex: 1;
		margin-right: 2%;
	}

	.tui-product-container:last-child {
		margin-right: 0;
	}

	.tui-pro-item {
		width: 100%;
		margin-bottom: 4%;
		background: #fff;
		box-sizing: border-box;
		border-radius: 12rpx;
		overflow: hidden;
	}

	.tui-pro-img {
		width: 100%;
		display: block;
		height: 375rpx;
	}

	.tui-pro-content {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		box-sizing: border-box;
		padding: 20rpx;
	}

	.tui-pro-tit {
		color: #2e2e2e;
		font-size: 26rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}

	.tui-pro-price {
		padding-top: 18rpx;
	}

	.tui-sale-price {
		font-size: 34rpx;
		font-weight: 500;
		color: #e41f19;
	}

	.tui-factory-price {
		font-size: 24rpx;
		color: #a0a0a0;
		text-decoration: line-through;
		padding-left: 12rpx;
	}

	.tui-pro-pay {
		padding-top: 10rpx;
		font-size: 24rpx;
		color: #656565;
	}

	.tui-btn-back {
		width: 88rpx;
		height: 88rpx;
		font-size: 26rpx;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
		background-color: rgba(0, 0, 0, .5);
		color: #fff;
		position: fixed;
		bottom: 160rpx;
		right: 30rpx;
		z-index: 9999;
	}
</style>
