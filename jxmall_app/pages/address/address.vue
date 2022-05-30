<template>
	<tui-page>
		<view class="tui-safe-area">
			<view class="tui-address">
				<block v-if="list.length==0&&!loading">
					<tui-no-data :fixed="false" imgUrl="/static/images/toast/img_nodata.png">没有地址请添加~</tui-no-data>
				</block>
				<block v-for="(item,index) in list" :key="index">
					<tui-list-cell padding="0">
						<view class="tui-address-flex">
							<view class="tui-address-left" @click="selectAddress(item)">
								<view class="tui-address-main">
									<view class="tui-address-name tui-ellipsis">{{item.name}}</view>
									<view class="tui-address-tel">{{item.mobile}}</view>
								</view>
								<view class="tui-address-detail">
									<view class="tui-address-label" v-if="item.is_default===1">默认</view>
									<text>{{item.address}}</text>
								</view>
							</view>
							<view class="tui-address-imgbox" @click="editAddr(item.id)">
								<image class="tui-address-img" src="/static/images/mall/my/icon_addr_edit.png" />
							</view>
						</view>
					</tui-list-cell>
				</block>
			</view>
			<!-- 新增地址 -->
			<view class="tui-address-new">
				<tui-button shadow shape="circle" type="danger" height="88rpx" @click="editAddr(0)">+ 新增收货地址
				</tui-button>
			</view>
		</view>

	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				loading: false,
				list: [],
				addressList: [1, 2, 3],
				type: ''
			}
		},
		onLoad: function(options) {
			if (options.type === 'SELECT') {
				this.type = options.type;
			}
			this.getList();
		},
		onShow: function() {
			this.getList();
		},
		methods: {
			getList() {
				this.loading = true;
				uni.showLoading({
					title:'获取数据...'
				})
				this.$request({
					url: '/api/user/address-list'
				}).then(res => {
					uni.hideLoading();
					this.loading = false;
					if (res.code == 0) {
						this.list = res.data.list
					}
				})
			},
			selectAddress(row) {
				if (this.type === 'SELECT') {
					uni.setStorageSync('SELECT_ADDRESS', row);
					uni.navigateBack({
						delta: 1
					})
				}

			},
			editAddr(id) {
				if (id) {
					uni.navigateTo({
						url: "/pages/address-edit/address-edit?id=" + id
					})
					return;
				}
				uni.navigateTo({
					url: "/pages/address-edit/address-edit"
				})
			}
		}
	}
</script>

<style>
	.tui-address {
		width: 100%;
		padding-top: 20rpx;
		padding-bottom: 160rpx;
	}

	.tui-address-flex {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.tui-address-main {
		width: 600rpx;
		height: 70rpx;
		display: flex;
		font-size: 30rpx;
		line-height: 86rpx;
		padding-left: 30rpx;
	}

	.tui-address-name {
		width: 120rpx;
		height: 60rpx;
	}

	.tui-address-tel {
		margin-left: 10rpx;
	}

	.tui-address-detail {
		font-size: 24rpx;
		word-break: break-all;
		padding-bottom: 25rpx;
		padding-left: 25rpx;
		padding-right: 120rpx;
	}

	.tui-address-label {
		padding: 5rpx 8rpx;
		flex-shrink: 0;
		background: #e41f19;
		border-radius: 6rpx;
		color: #fff;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 25rpx;
		line-height: 25rpx;
		transform: scale(0.8);
		transform-origin: center center;
		margin-right: 6rpx;
	}

	.tui-address-imgbox {
		width: 80rpx;
		height: 100rpx;
		position: absolute;
		display: flex;
		justify-content: center;
		align-items: center;
		right: 10rpx;
	}

	.tui-address-img {
		width: 36rpx;
		height: 36rpx;
	}

	.tui-address-new {
		width: 100%;
		position: fixed;
		left: 0;
		bottom: 0;
		z-index: 999;
		padding: 20rpx 25rpx 30rpx;
		box-sizing: border-box;
		background: #fafafa;
	}

	.tui-safe-area {
		padding-bottom: env(safe-area-inset-bottom);
	}
</style>
