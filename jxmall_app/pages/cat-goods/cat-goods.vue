<template>
	<tui-page>
		<view class="container">
			<view class="tui-searchbox">
				<view class="tui-search-input" @tap="search">
					<icon type="search" :size="13" color="#999"></icon>
					<text class="tui-search-text">输入内容进行搜索</text>
				</view>
			</view>
			<scroll-view scroll-y scroll-with-animation class="tab-view" :scroll-into-view="scrollViewId"
				:style="{ height: height + 'px'}">
				<view :id="`id_${index}`" v-for="(item, index) in list" :key="index" class="tab-bar-item"
					:class="[currentTab == index ? 'active' : '']" :data-current="index" @tap.stop="swichNav">
					<text>{{ item.name }}</text>
				</view>
			</scroll-view>


			<scroll-view scroll-y class="right-box" style="background-color: #F0F0F0;"
				:style="{ height: height + 'px'}">
				<!--内容部分 start 自定义可删除-->

				<view class="page-view">
					<block v-for="(item, index) in second_list" :key="index">
						<view class="class-box">
							<view class="class-item">
								<view class="flex-x-between">
									<view class="class-name" style="font-weight: bold;padding: 10rpx 0;">{{ item.name }}
									</view>
									<view class="class-name flex-y-center"
										@click="$T.go('/pages/goods-list/goods-list?cat_id='+item.id)"
										style="color: #555555;">查看更多 <tui-icon name="arrowright" size="15"></tui-icon>
									</view>
								</view>
								<view class="g-container">
									<view v-if="item.list.length" v-for="goods in item.list"
										style="margin-bottom: 20rpx;background-color: #FFFFFF;padding: 20rpx;border-radius: 20rpx;">
										<view class="flex-row flex-x-between" style="width: 100%;">
											<view class="flex-row ">
												<image :src="goods.cover_pic" class="g-image" mode="aspectFill"
													style="border-radius: 10rpx;overflow: hidden;" />
												<view style="padding: 0 20rpx;">
													<view class="g-title" style="font-weight:bold;font-size:10pt;white-space:nowrap;overflow:hidden;width:189rpx;text-overflow: ellipsis;">
														{{goods.name}}
													</view>
													<view class="danger-color"
														style="font-size: 10pt;padding: 10rpx 0;font-weight: bold;"
														v-if="goods.is_integral==0">
														￥{{goods.price}}
													</view>
													<view class="danger-color"
														style="font-size: 10pt;color: #E32322;padding: 10rpx 0;font-weight: bold;"
														v-if="goods.is_integral==1">
														{{goods.use_integral}}积分
													</view>
													<view
														style="font-size: 10pt;color: #B2B2B2;text-decoration: line-through;">
														￥{{goods.origin_price}}
													</view>
												</view>
											</view>
											<tui-button shape="circle" type="danger" width="100rpx" height="50rpx"
												size="20" @click="$T.go('/pages/goods/goods?id='+goods.id)">购买
											</tui-button>
										</view>
									</view>
									<view v-if="item.list.length==0"
										style="width: 100%;font-size: 9pt;color: #666666;text-center;">
										暂无商品
									</view>
								</view>
							</view>
						</view>
					</block>

					<view v-if="second_list.length==0" style="width: 100%;font-size: 9pt;color: #666666;text-center;">
						暂无商品
					</view>
				</view>
				<!--内容部分 end 自定义可删除-->
			</scroll-view>
		</view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				tabbar: [],
				list: [],
				second_list: [],
				height: 0, //scroll-view高度
				top: 0,
				currentTab: 0, //预设当前项的值
				scrollViewId: "id_0"
			};
		},
		onLoad: function(options) {
			setTimeout(() => {
				uni.getSystemInfo({
					success: res => {
						this.height = res.windowHeight;
						this.height -= 50; //增加50px 防止下面存在tabbar 遮蔽了
					}
				});
			}, 50);

			let cat = uni.getStorageSync('CAT');
			if (cat) {
				this.list = cat;
				if (this.list.length) {
					if (this.list[0].children.length) {
						this.second_list = this.list[0].children;
					}
				}
			}
			this.getCat();
		},
		methods: {
			getCat() {
				this.$request({
					url: '/api/goods/cat-goods'
				}).then(res => {
					if (res.code == 0) {
						uni.setStorageSync('CAT', res.data.list)
						this.list = res.data.list
						if (this.list.length) {
							if (this.list[0].children.length && this.list[0].children.length) {
								this.second_list = this.list[0].children;
							}
						}
					}
				})
			},
			// 点击标题切换当前页时改变样式
			swichNav: function(e) {
				let cur = e.currentTarget.dataset.current;
				if (this.currentTab == cur) {
					return false;
				} else {
					this.second_list = this.list[cur].children;
					console.log(this.second_list);
					this.currentTab = cur;

				}

			},
			//判断当前滚动超过一屏时，设置tab标题滚动条。
			checkCor: function() {
				if (this.currentTab > 6) {
					this.scrollViewId = `id_${this.currentTab - 2}`;
				} else {
					this.scrollViewId = `id_0`;
				}
			},
			detail(e) {
				uni.navigateTo({
					url: '/pages/goods-list/goods-list?cat_id=' + e.id
				});
			},

			search: function() {
				uni.navigateTo({
					url: '/pages/search/search'
				});
			}
		}
	};
</script>

<style>
	page {
		background: #fcfcfc;
	}

	/* 左侧导航布局 start*/

	/* 隐藏scroll-view滚动条*/

	::-webkit-scrollbar {
		width: 0;
		height: 0;
		color: transparent;
	}

	.tui-searchbox {
		width: 100%;
		height: 92rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		background: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		position: fixed;
		left: 0;
		top: 0;

		z-index: 100;
	}

	.tui-searchbox::after {
		content: '';
		position: absolute;
		border-bottom: 1rpx solid #d2d2d2;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
		bottom: 0;
		right: 0;
		left: 0;
	}

	.tui-search-input {
		width: 100%;
		height: 60rpx;
		background: #f1f1f1;
		border-radius: 30rpx;
		font-size: 26rpx;
		color: #999;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-search-text {
		padding-left: 16rpx;
	}

	.tab-view {
		/* height: 100%; */
		width: 200rpx;
		position: fixed;
		left: 0;
		z-index: 10;
		top: 100rpx;

		background: #f6f6f6;
	}

	.tab-bar-item {
		width: 200rpx;
		height: 110rpx;
		background: #f6f6f6;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 26rpx;
		color: #444;
		font-weight: 400;
	}

	.active {
		position: relative;
		color: #000;
		font-size: 30rpx;
		font-weight: 600;
		background: #fcfcfc;
	}

	.active::before {
		content: '';
		position: absolute;
		border-left: 8rpx solid #e41f19;
		height: 30rpx;
		left: 0;
	}

	/* 左侧导航布局 end*/

	.right-box {
		width: 100%;
		position: fixed;
		padding-left: 220rpx;
		box-sizing: border-box;
		left: 0;
		top: 100rpx;
	}

	.page-view {
		width: 100%;
		overflow: hidden;
		box-sizing: border-box;
		padding-bottom: env(safe-area-inset-bottom);
	}

	.swiper {
		width: 100%;
		height: 220rpx;
		border-radius: 12rpx;
		overflow: hidden;
		transform: translateZ(0);
	}

	/* #ifdef MP-WEIXIN */
	.swiper .wx-swiper-dot {
		width: 8rpx;
		height: 8rpx;
		display: inline-flex;
		background: none;
		justify-content: space-between;
	}

	.swiper .wx-swiper-dot::before {
		content: '';
		flex-grow: 1;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 16rpx;
		overflow: hidden;
	}

	.swiper .wx-swiper-dot-active::before {
		background: #fff;
	}

	.swiper .wx-swiper-dot.wx-swiper-dot-active {
		width: 16rpx;
	}

	/* #endif */

	/* #ifndef MP-WEIXIN */
	>>>.swiper .uni-swiper-dot {
		width: 8rpx;
		height: 8rpx;
		display: inline-flex;
		background: none;
		justify-content: space-between;
	}

	>>>.swiper .uni-swiper-dot::before {
		content: '';
		flex-grow: 1;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 16rpx;
		overflow: hidden;
	}

	>>>.swiper .uni-swiper-dot-active::before {
		background: #fff;
	}

	>>>.swiper .uni-swiper-dot.uni-swiper-dot-active {
		width: 16rpx;
	}

	/* #endif */

	.slide-image {
		width: 100%;
		height: 220rpx;
	}

	.class-box {}

	.class-item {

		width: 100%;
		box-sizing: border-box;
		padding:0 20rpx;
	 
		border-radius: 12rpx;
	}

	.class-name {
		font-size: 22rpx;
	}




	.g-image {
		width: 120rpx;
		height: 120rpx;
	}

	.g-title {
		font-size: 22rpx;
	}
</style>
