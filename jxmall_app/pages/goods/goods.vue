<template>
	<tui-page ref="page">

		<!--header-->
		<!-- #ifndef H5 -->
		<view class="tui-header-box" :style="{ height: height + 'px', background: 'rgba(255,255,255,' + opcity + ')' }">
			<view class="tui-header" :style="{ paddingTop: top + 'px', opacity: opcity }">商品详情</view>
			<view class="tui-header-icon" :style="{ marginTop: top + 'px' }">
				<view class="tui-icon-box" :style="{ backgroundColor: 'rgba(0, 0, 0,' + iconOpcity + ')' }" @tap="back">
					<tui-icon name="arrowleft" :size="30" :color="opcity >= 1 ? '#000' : '#fff'"></tui-icon>
				</view>
			</view>
		</view>
		<!-- #endif -->
		<!--header-->

		<!--banner-->
		<view class="tui-banner-swiper" v-if="goods_info">
			<swiper :autoplay="true" :interval="5000" :duration="150" :circular="true"
				:style="{ height: scrollH + 'px' }" @change="bannerChange">
				<block v-for="(item, index) in goods_info.pic_list" :key="index">
					<swiper-item :data-index="index" @tap.stop="previewImage">
						<image :src="item" mode="aspectFill" class="tui-slide-image"
							:style="{ height: scrollH + 'px' }" />
					</swiper-item>
				</block>
			</swiper>
			<view class="tui-banner-tag">
				<tui-tag padding="12rpx 18rpx" type="translucent" shape="circleLeft" :scaleMultiple="0.82" originRight>
					{{ bannerIndex + 1 }}/{{ goods_info.pic_list.length }}
				</tui-tag>
			</view>
		</view>

		<!--banner-->

		<view class="tui-pro-detail" v-if="goods&&goods_info">
			<view class="tui-product-title tui-border-radius">
				<view class="tui-pro-pricebox tui-padding">
					<view class="tui-pro-price">
						<view v-if="!goods.is_negotiable">
							<text>￥</text>
							<text class="tui-price">{{goods.price}}</text>
						</view>
						<view v-if="goods.is_negotiable">
							<text class="tui-price">面议</text>
						</view>
						<tui-tag padding="10rpx 20rpx" size="24rpx" plain type="high-green" shape="circle"
							:scaleMultiple="0.8">新品</tui-tag>
					</view>
					<view class="tui-collection tui-size flex-row" @click="sharePoster">
						<image src="../../static/icons/qrcode.png" style="width: 35rpx;height: 35rpx;"></image>
					</view>
				</view>
				<view class="tui-original-price tui-gray flex-y-center" v-if="!goods.is_negotiable">
					<view>
						原价:
						<text class="tui-line-through" style="padding: 0 10rpx;">￥{{goods.origin_price}}</text>
					</view>
					<view style="padding: 0 20rpx;">
						销量:
						<text style="padding: 0 10rpx;">{{goods.virtual_sales}}</text>
					</view>
				</view>
				<view class="tui-pro-titbox flex-y-center">
					<view class="tui-pro-title" style="font-weight: bold;">{{goods.name}}</view>
					<button open-type="share" class="tui-share-btn tui-share-position">
						<tui-tag type="gray" shape="circleLeft" padding="12rpx 16rpx">
							<view class="tui-share-box">
								<tui-icon name="partake" color="#999" :size="15"></tui-icon>
								<text class="tui-share-text tui-gray tui-size">分享</text>
							</view>
						</tui-tag>
					</button>
				</view>
				<view class="tui-padding" v-if="goods_info.remarks">
					<view class="tui-sub-title tui-size tui-gray">{{goods_info.remarks}}</view>

				</view>
			</view>

			<view class="tui-discount-box tui-radius-all tui-mtop" v-if="coupon_list.length">
				<view class="tui-list-cell" @tap="coupon">
					<view class="tui-bold tui-cell-title">领券</view>
					<view class="tui-flex-center">
						<tui-tag margin="0 20rpx 0 0" type="red" shape="circle" padding="12rpx 24rpx" size="24rpx"
							v-for="coupon in coupon_list">{{coupon.name}}</tui-tag>
						<!-- 	<tui-tag margin="0 0 0 20rpx" type="red" padding="12rpx 24rpx" size="24rpx" shape="circle">满59减5</tui-tag> -->
					</view>
					<view class="tui-ml-auto">
						<tui-icon name="more-fill" :size="20" color="#666"></tui-icon>
					</view>
				</view>

			</view>

			<view class="tui-basic-info tui-mtop tui-radius-all">
				<view class="tui-list-cell" @tap="showPopup">
					<view class="tui-bold tui-cell-title">已选</view>
					<view class="tui-selected-box" v-if="goods.is_attr==0">规格：默认</view>
					<view class="tui-selected-box" v-if="goods.is_attr==1">
						<text v-if="!select_attr">未选择</text>

						<block v-if="select_attr!=null">
							<text v-for="(item,index) in select_attr.attr_list">
								<block v-if="index>0">
									/
								</block>
								{{item.attr_name}}
							</text>

						</block>
					</view>
					<view class="tui-ml-auto">
						<tui-icon name="more-fill" :size="20" color="#666"></tui-icon>
					</view>
				</view>
			</view>

			<view class="tui-cmt-box tui-mtop tui-radius-all">
				<view class="tui-list-cell tui-last tui-between">
					<view class="tui-bold tui-cell-title">评价</view>
					<view class="tui-flex-center" @tap="commentList" v-if="comment">
						<text class="tui-cmt-all">查看全部</text>
						<tui-icon name="more-fill" :size="20" color="#ff201f"></tui-icon>
					</view>
				</view>

				<block v-if="comment">
					<view class="tui-cmt-content tui-padding">
						<view class="tui-cmt-user">
							<image :src="comment.avatar_url" class="tui-acatar"></image>
							<view>{{comment.nickname}}</view>
						</view>
						<view class="tui-cmt">{{comment.content?comment.content:'暂无评价内容'}}</view>
						<!-- 	<view class="tui-attr">颜色：叠层钛钢流苏耳环（A74）</view> -->
					</view>
					<view class="tui-cmt-btn">
						<tui-button width="240rpx" height="64rpx" :size="24" type="black" plain shape="circle"
							@click="commentList">查看全部评价</tui-button>
					</view>
				</block>
				<block v-if="!comment">
					<view class="tui-cmt-content tui-padding" style="text-align: center;padding-bottom: 20rpx;">
						暂无评价
					</view>
				</block>
			</view>
			<view class="tui-product-img tui-radius-all" v-if="goods_info">
				<tui-rich-text :value="goods_info.detail"></tui-rich-text>
			</view>
			<tui-nomore text="已经到最底了" backgroundColor="#f7f7f7"></tui-nomore>
			<view class="tui-safearea-bottom"></view>
		</view>



		<!--底部操作栏-->
		<view class="tui-operation" style="width: 100%;" :style="{height:is_ios?'172rpx':'110rpx'}" v-if="goods">
			<block v-if="$util.getSetting('is_show_add_cart')==1">
				<view class="tui-operation-left tui-col-5">
					<view class="tui-operation-item" hover-class="tui-opcity" :hover-stay-time="150" @click="goHome">
						<tui-icon name="shop" :size="22" color="#333"></tui-icon>
						<view class="tui-operation-text tui-scale-small">首页</view>
					</view>
					<view class="tui-operation-item" hover-class="tui-opcity" :hover-stay-time="150" @tap="collecting">
						<tui-icon :name="collected ? 'like-fill' : 'like'" :color="collected ? '#ff201f' : '#333'"
							:size="22"></tui-icon>
						<view class="tui-operation-text" :class="{'tui-icon-red':collected}">收藏</view>
					</view>
					<view class="tui-operation-item" hover-class="tui-opcity" :hover-stay-time="150"
						@click="pageTo('/pages/cart/cart',true)">
						<tui-icon name="cart" :size="22" color="#333"></tui-icon>
						<view class="tui-operation-text tui-scale-small">购物车</view>
						<tui-badge type="red" absolute :scaleRatio="0.8" right="10rpx" top="-4rpx" v-if="cart_count>0">
							{{cart_count}}
						</tui-badge>
					</view>
				</view>
				<view class="tui-operation-right tui-right-flex tui-col-7 tui-btnbox-4"
					:style="{height:is_ios?'172rpx':'110rpx'}" style="width:90%;" v-if="goods&&!goods.is_negotiable">
					<view class="tui-flex-1">
						<tui-button height="68rpx" :size="26" type="warning" shape="circle" @click="showPopup">加入购物车
						</tui-button>
					</view>
					<view class="tui-flex-1 ">
						<tui-button height="68rpx" :size="26" type="danger" shape="circle" @click="submit">立即购买
						</tui-button>
					</view>
				</view>
				<view class="tui-operation-right tui-right-flex tui-col-7 tui-btnbox-4" style="width:90%;" v-else>
					<view class="tui-flex-1 ">
						<tui-button height="68rpx" :size="26" type="warning" shape="circle">面议</tui-button>
					</view>
				</view>
			</block>
			<block v-if="$util.getSetting('is_show_add_cart')==0">
				<view class="tui-operation-left tui-col-3">
					<view class="tui-operation-item" hover-class="tui-opcity" :hover-stay-time="150" @click="goHome">
						<tui-icon name="shop" :size="22" color="#333"></tui-icon>
						<view class="tui-operation-text tui-scale-small">首页</view>
					</view>
					<view class="tui-operation-item" hover-class="tui-opcity" :hover-stay-time="150" @tap="collecting">
						<tui-icon :name="collected ? 'like-fill' : 'like'" :color="collected ? '#ff201f' : '#333'"
							:size="22"></tui-icon>
						<view class="tui-operation-text" :class="{'tui-icon-red':collected}">收藏</view>
					</view>
				</view>
				<view class="tui-operation-right tui-right-flex tui-col-9 tui-btnbox-4"
					:style="{height:is_ios?'172rpx':'110rpx'}" style="width:90%;" v-if="goods&&!goods.is_negotiable">
			
					<view class="tui-flex-1 ">
						<tui-button height="68rpx" :size="26" type="danger" shape="circle" @click="submit">立即购买
						</tui-button>
					</view>
				</view>
				<view class="tui-operation-right tui-right-flex tui-col-8 tui-btnbox-4" style="width:90%;" v-else>
					<view class="tui-flex-1 ">
						<tui-button height="68rpx" :size="26" type="warning" shape="circle">面议</tui-button>
					</view>
				</view>
			</block>
		</view>

		<!--底部操作栏--->



		<!--底部选择层-->
		<tui-bottom-popup :show="popupShow" @close="hidePopup">
			<view class="tui-popup-box" v-if="goods">
				<view class="tui-product-box tui-padding">
					<block v-if="select_attr&&select_attr.pic_url">
						<image :src="select_attr.pic_url" class="tui-popup-img"></image>
					</block>
					<block v-else>
						<image :src="goods.cover_pic" class="tui-popup-img"></image>
					</block>
					<view class="tui-popup-price" v-if="!goods.is_negotiable">
						<view class="tui-amount tui-bold" v-if="select_attr">￥{{select_attr.price}}</view>
						<view class="tui-amount tui-bold" v-if="!select_attr">￥{{goods.price}}</view>
						<view class="tui-number" v-if="select_attr&&select_attr.no">编号:{{select_attr.no}}</view>
					</view>
					<view class="tui-popup-price" v-else>
						<view class="tui-amount tui-bold" v-if="select_attr">面议</view>
						<view class="tui-amount tui-bold" v-if="!select_attr">面议</view>
						<view class="tui-number" v-if="select_attr&&select_attr.no">编号:{{select_attr.no}}</view>
					</view>
				</view>
				<scroll-view scroll-y class="tui-popup-scroll" v-if="attr_group_list.length">
					<view class="tui-scrollview-box">
						<view v-if="attr_group_list.length">
							<view v-for="(item,index) in attr_group_list">
								<view class="tui-bold tui-attr-title">{{item.attr_group_name}}</view>
								<view class="tui-attr-box">
									<view class="tui-attr-item"
										:class="attr.active||goods.is_attr==0?'tui-attr-active':''"
										@click="attrClick(index,i)" v-for="(attr,i) in item.attr_list">
										{{attr.attr_name}}
									</view>
								</view>
							</view>
						</view>
						<view class="tui-number-box tui-bold tui-attr-title">
							<view class="tui-attr-title">数量</view>
							<tui-numberbox :max="99" :min="1" :value="num" @change="numChange"></tui-numberbox>
						</view>
					</view>
				</scroll-view>
				<view class="tui-operation tui-operation-right tui-right-flex tui-popup-btn"
					:style="{height:is_ios?'172rpx':'110rpx'}" v-if="!goods.is_negotiable">
					<view class="tui-flex-1">
						<tui-button height="72rpx" :size="28" type="warning" shape="circle" @click="addCart">加入购物车
						</tui-button>
					</view>
					<view class="tui-flex-1">
						<tui-button height="72rpx" :size="28" type="danger" shape="circle" @click="submit">立即购买
						</tui-button>
					</view>
				</view>
				<view class="tui-right">
					<tui-icon name="close-fill" color="#999" :size="20" @click="hidePopup"></tui-icon>
				</view>
			</view>
		</tui-bottom-popup>
		<!--海报弹出-->
		<tui-modal-poster :url="poster_url" :showPoster="showPosterModal" @closePoster="closePoster"></tui-modal-poster>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				id: 0,
				height: 64, //header高度
				top: 26, //标题图标距离顶部距离
				scrollH: 0, //滚动总高度
				opcity: 0,
				iconOpcity: 0.5,
				goods: null,
				goods_info: null,
				attr_group_list: [],
				attr_list: [],
				bannerIndex: 0,
				attr_group_index: 0,
				attr_index: 0,
				poster_url: '',
				showPosterModal: false,
				topMenu: [{
						icon: 'message',
						text: '消息',
						size: 26,
						badge: 3
					},
					{
						icon: 'home',
						text: '首页',
						size: 23,
						badge: 0
					},
					{
						icon: 'people',
						text: '我的',
						size: 26,
						badge: 0
					},
					{
						icon: 'cart',
						text: '购物车',
						size: 23,
						badge: 2
					},
					{
						icon: 'kefu',
						text: '客服小蜜',
						size: 26,
						badge: 0
					},
					{
						icon: 'feedback',
						text: '我要反馈',
						size: 23,
						badge: 0
					},
					{
						icon: 'share',
						text: '分享',
						size: 26,
						badge: 0
					}
				],
				coupon_list: [],
				is_no_login: false,
				menuShow: false,
				popupShow: false,
				value: 1,
				collected: false,
				select_attr_list: [],
				select_attr: null,
				num: 1,
				cart_count: 0,
				comment: null,
				is_ios: false,
			};
		},
		onShow: function() {
			this.select_attr = null;
			this.num = 1;
			this.select_attr_list = [];
			for (let index in this.attr_group_list) {
				for (let i in this.attr_group_list[index].attr_list) {
					this.attr_group_list[index].attr_list[i].active = false;
				}
			}

		},
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

			setTimeout(() => {
				uni.getSystemInfo({
					success: res => {
						console.log(res);
						this.width = obj.left || res.windowWidth;
						this.height = obj.top ? obj.top + obj.height + 8 : res.statusBarHeight +
							44;
						this.top = obj.top ? obj.top + (obj.height - 32) / 2 : res
							.statusBarHeight + 6;
						this.scrollH = res.windowWidth;
						console.log(res.platform);
						if (res.platform == 'ios' || res.platform == 'devtools') {
							this.is_ios = true;
						}
					}
				});
			}, 0);
			this.id = options.id
			this.getGoods();
			this.getCartCount();
			this.getCouponList();
		},
		methods: {

			closePoster() {
				this.showPosterModal = false;
			},
			sharePoster() {

				if (this.is_no_login) {

					this.$refs.page.showLoginModalWindow();
					return;

				}
				uni.showLoading({
					title: '正在生成海报....'
				})
				if (this.poster_url) {
					uni.hideLoading();
					this.showPosterModal = !this.showPosterModal;
					return;
				}
				this.$request({
					url: '/api/poster/goods-poster',
					data: {
						goods_id: this.id,
					},
					method: 'get'
				}).then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						this.poster_url = res.data.url
						this.showPosterModal = !this.showPosterModal;
					}
					if (res.code == -1) {
						this.is_no_login = true;
						this.$refs.page.showLoginModalWindow();
					}

				})

			},
			goHome() {
				uni.redirectTo({
					url: '/pages/index/index'
				})
			},
			getCartCount() {
				this.$request({
					url: '/api/cart/count',
				}).then(res => {
					if (res.code == 0) {
						this.cart_count = res.data.count
					}
				})

			},
			getGoods() {
				this.$request({
					url: '/api/goods/goods',
					data: {
						id: this.id,
					}
				}).then(res => {
					console.log(res);
					if (res.code == 0) {
						this.goods = res.data.goods;
						this.goods_info = res.data.info;
						this.attr_group_list = res.data.attr_group_list;
						this.attr_list = res.data.attr_list;
						this.collected = res.data.is_favorite == 1 ? true : false;
						this.comment = res.data.comment;
						//#ifdef H5
						if (this.$wechatSdk.isWechat()) {
							this.$wechatSdk.share(null, {
								share_title: this.goods.share_title ? this.goods.share_title : this.goods
									.name,
								share_desc: this.goods.share_desc ? this.goods.share_desc : this.goods
									.name,
								share_pic: this.goods.cover_pic
							});
						}
						//#endif
					}
					if (res.code == 1) {

						uni.showModal({
							title: '提示',
							content: res.msg,
							success: function(e) {
								uni.navigateBack({
									delta: 1
								})
							}
						})
					}
				})
			},
			attrClick(group_index, attr_index) {
				for (let i in this.attr_group_list[group_index].attr_list) {
					if (i == attr_index) {
						this.attr_group_list[group_index].attr_list[i].active = true;
					} else {
						this.attr_group_list[group_index].attr_list[i].active = false;
					}
				}
				if (this.goods.is_attr == 1) {
					this.computeAttr();
				}
				this.$forceUpdate();
			},
			computeAttr() {
				this.select_attr = null;
				this.select_attr_list = [];
				for (let index in this.attr_group_list) {
					for (let i in this.attr_group_list[index].attr_list) {
						if (this.attr_group_list[index].attr_list[i].active) {
							this.select_attr_list.push({
								attr_group_id: this.attr_group_list[index].attr_group_id,
								attr_id: this.attr_group_list[index].attr_list[i].attr_id
							});
						}
					}
				}
				if (this.select_attr_list.length == this.attr_group_list.length) {

					for (let index in this.attr_list) {
						let flag = false;
						let times = 0;
						for (let i in this.attr_list[index].attr_list) {
							let item = this.attr_list[index].attr_list[i];
							for (let j in this.select_attr_list) {
								let select = this.select_attr_list[j];
								if (item.attr_group_id == select.attr_group_id && item.attr_id == select.attr_id) {
									times++;
								}
							}
						}
						if (times == this.select_attr_list.length) {
							this.select_attr = this.attr_list[index]
							return;
						}
					}
				}
			},
			bannerChange: function(e) {
				this.bannerIndex = e.detail.current;
			},
			previewImage: function(e) {
				let index = e.currentTarget.dataset.index;
				uni.previewImage({
					current: this.goods_info.pic_list[index],
					urls: this.goods_info.pic_list
				});
			},
			back: function() {
				uni.navigateBack();
			},
			openMenu: function() {
				this.menuShow = true;
			},
			closeMenu: function() {
				this.menuShow = false;
			},
			showPopup: function() {
				this.popupShow = true;
			},
			hidePopup: function() {
				this.popupShow = false;
			},
			numChange: function(e) {
				this.num = e.value;
			},
			collecting: function() {

				this.$request({
					url: '/api/goods/favorite',
					data: {
						goods_id: this.id,
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.collected = !this.collected;
					}
					if (res.code == -1) {
						this.is_no_login = true;
						this.$refs.page.showLoginModalWindow();
						this.collected = false;
					}

				})
			},
			commentList: function() {
				uni.navigateTo({
					url: "/pages/comment-list/comment-list?goods_id=" + this.id
				})
			},
			pageTo(url, redirect) {

				if (redirect) {
					uni.redirectTo({
						url: url
					})
					return;
				}

				uni.navigateTo({
					url: url
				})
				return;
			},
			btnTopMenu(index) {
				this.closeMenu()
				if (index == 4) {
					uni.makePhoneCall({
						phoneNumber: "10086"
					})
				} else if (index == 6) {
					// #ifdef MP
					this.common()
					// #endif

					// #ifndef MP
					this.onShare()
					// #endif
				} else {
					let url = {
						0: '../message/message',
						1: "../mall/mall",
						2: '../my/my',
						3: '../shopcart/shopcart',
						5: '/pages/my/feedback/feedback?page=mall'
					} [index];
					url && this.tui.href(url)
				}
			},
			submit() {
				if (this.is_no_login) {
					this.$refs.page.showLoginModalWindow();
					return;
				}
				if (!this.popupShow) {
					this.popupShow = true;
					return;
				}
				if (this.select_attr_list.length < this.attr_group_list.length && this.goods.is_attr == 1) {
					if (this.popupShow) {
						uni.showToast({
							title: '请选择规格'
						})
					}
					return;
				}
				if (this.goods.is_attr) {
					if (this.num > this.select_attr.stock) {
						uni.showToast({
							title: '购买数量超出库存总量'
						})
						this.showPopup();
						return;
					}
				}
				if (this.goods.is_attr == 0) {
					if (this.num > this.goods.stock) {
						this.showPopup();
						uni.showToast({
							title: '购买数量超出库存总量'
						})
						return;
					}
				}
				let goods_attr_id = 0;
				if (this.goods.is_attr) {
					goods_attr_id = this.select_attr.id;

				}
				let mch_list = [{
					mch_id: this.goods.mch_id,
					goods_list: [{
						goods_id: this.id,
						num: this.num,
						goods_attr_id: goods_attr_id
					}]
				}];
				uni.setStorageSync('ORDER_SUBMIT_DATA', mch_list);
				uni.navigateTo({
					url: '/pages/order-submit/order-submit'
				});
			},
			addCart() {
				if (this.goods.is_attr == 1) {
					if (this.popupShow) {
						uni.showToast({
							title: '请选择规格'
						})
					}
					if (this.select_attr_list.length < this.attr_group_list.length) {
						this.popupShow = true;
						return;
					}
				}
				this.popupShow = false;
				if (this.is_no_login) {
					this.$refs.page.showLoginModalWindow();
					return;
				}
				if (this.goods.is_attr) {
					if (this.num > this.select_attr.stock) {
						uni.showToast({
							title: '购买数量超出库存总量'
						})
						return;
					}

					this.$request({
						url: '/api/cart/add-cart',
						data: {
							goods_id: this.id,
							goods_attr_id: this.select_attr.id,
							num: this.num
						},
						method: 'post'
					}).then(res => {
						if (res.code == 0) {
							uni.showToast({
								title: '添加成功'
							})
						}
						if (res.code == -1) {
							this.$refs.page.showLoginModalWindow();
							this.is_no_login = true;
							return;
						}
					})

				}
				if (this.goods.is_attr == 0) {
					if (this.num > this.goods.stock) {
						uni.showToast({
							title: '购买数量超出库存总量'
						})
						return;
					}
					this.$request({
						url: '/api/cart/add-cart',
						data: {
							goods_id: this.id,
							goods_attr_id: 0,
							num: this.num
						},
						method: 'post'
					}).then(res => {
						if (res.code == 0) {
							uni.showToast({
								title: '添加成功'
							})
						}
						if (res.code == -1) {
							this.$refs.page.showLoginModalWindow();
							this.is_no_login = true;
							return;
						}
					})
				}
			},
			getCouponList() {
				uni.showLoading({
					title: '加载中...'
				})
				this.$request({
					url: "/api/coupon/coupon-list",
					data: {
						limit: 5,
						gid: this.id
					}
				}).then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						this.coupon_list = res.data.list;
					}

				})
			},

			coupon() {
				uni.navigateTo({
					url: '../coupon/coupon'
				});
			},
			onShare() {
				//#ifdef APP-PLUS
				plus.share.sendWithSystem({
						content: 'ThorUI商城模板',
						href: 'https://www.thorui.cn/'
					},
					function() {
						console.log('分享成功');
					},
					function(e) {
						console.log('分享失败：' + JSON.stringify(e));
					}
				);
				//#endif
			}
		},
		onShareAppMessage: function(e) {

			let user_info = uni.getStorageSync('USER');
			let path = '';
			if (user_info) {
				path = '/pages/goods/goods?id=' + this.id + '&pid=' + user_info.id;
			} else {
				path = '/pages/goods/goods?id=' + this.id + '&pid=0';
			}
			console.log(path);
			return {
				title: this.goods.name,
				path: path
			}
		},
		onPageScroll(e) {
			let scroll = e.scrollTop <= 0 ? 0 : e.scrollTop;
			let opcity = scroll / this.scrollH;
			if (this.opcity >= 1 && opcity >= 1) {
				return;
			}
			this.opcity = opcity;
			this.iconOpcity = 0.5 * (1 - opcity < 0 ? 0 : 1 - opcity);
		}
	};
</script>

<style>
	page {
		background-color: #f7f7f7;
	}

	.container {
		padding-bottom: 110rpx;
	}

	.tui-header-box {
		width: 100%;
		position: fixed;
		left: 0;
		top: 0;
		z-index: 995;
	}

	.tui-header {
		width: 100%;
		font-size: 18px;
		line-height: 18px;
		font-weight: 500;
		height: 32px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-header-icon {
		position: fixed;
		top: 0;
		left: 10px;
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		height: 32px;
		transform: translateZ(0);
		z-index: 9999;
	}

	.tui-header-icon .tui-badge {
		background: #e41f19 !important;
		position: absolute;
		right: -4px;
	}

	.tui-icon-ml {
		margin-left: 20rpx;
	}

	.tui-icon-box {
		position: relative;
		height: 20px !important;
		width: 20px !important;
		padding: 6px !important;

		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-banner-swiper {
		position: relative;
	}

	.tui-banner-tag {
		position: absolute;
		color: #fff;
		bottom: 30rpx;
		right: 0;
	}

	.tui-slide-image {
		width: 100%;
		display: block;
	}

	/*顶部菜单*/

	.tui-menu-box {
		box-sizing: border-box;
	}

	.tui-menu-header {
		font-size: 34rpx;
		color: #fff;
		height: 32px;
		display: flex;
		align-items: center;
	}

	.tui-menu-itembox {
		color: #fff;
		padding: 40rpx 10rpx 0 10rpx;
		box-sizing: border-box;
		display: flex;
		flex-wrap: wrap;
		font-size: 26rpx;
	}

	.tui-menu-item {
		width: 22%;
		height: 160rpx;
		border-radius: 24rpx;
		display: flex;
		align-items: center;
		flex-direction: column;
		justify-content: center;
		background: rgba(0, 0, 0, 0.4);
		margin-right: 4%;
		margin-bottom: 4%;
	}

	.tui-menu-item:nth-of-type(4n) {
		margin-right: 0;
	}

	.tui-badge-box {
		position: relative;
	}

	.tui-badge-box .tui-badge-class {
		position: absolute;
		top: -8px;
		right: -8px;
	}

	.tui-msg-badge {
		top: -10px;
	}

	.tui-icon-up_box {
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-menu-text {
		padding-top: 12rpx;
	}

	.tui-opcity .tui-menu-text,
	.tui-opcity .tui-badge-box {
		opacity: 0.5;
		transition: opacity 0.2s ease-in-out;
	}

	/*顶部菜单*/

	/*内容 部分*/

	.tui-padding {
		padding: 0 30rpx;
		box-sizing: border-box;
	}

	.tui-ml-auto {
		margin-left: auto;
	}

	/* #ifdef H5 */
	.tui-ptop {
		padding-top: 44px;
	}

	/* #endif */

	.tui-size {
		font-size: 24rpx;
		line-height: 24rpx;
	}

	.tui-gray {
		color: #999;
	}

	.tui-icon-red {
		color: #ff201f;
	}

	.tui-border-radius {
		border-bottom-left-radius: 24rpx;
		border-bottom-right-radius: 24rpx;
		overflow: hidden;
	}

	.tui-radius-all {
		border-radius: 24rpx;
		overflow: hidden;
	}

	.tui-mtop {
		margin-top: 26rpx;
	}

	.tui-pro-detail {
		box-sizing: border-box;
		color: #333;
	}

	.tui-product-title {
		background: #fff;
		padding: 30rpx 0;
	}

	.tui-pro-pricebox {
		display: flex;
		align-items: center;
		justify-content: space-between;
		color: #ff201f;
		font-size: 36rpx;
		font-weight: bold;
		line-height: 44rpx;
	}

	.tui-pro-price {
		display: flex;
		align-items: center;
	}

	.tui-price {
		font-size: 58rpx;
	}

	.tui-original-price {
		font-size: 26rpx;
		line-height: 26rpx;
		padding: 10rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-line-through {
		text-decoration: line-through;
	}

	.tui-collection {
		color: #333;
		display: flex;
		align-items: center;
		flex-direction: column;
		justify-content: center;
		height: 44rpx;
	}

	.tui-scale-collection {
		transform: scale(0.7);
		transform-origin: center 90%;
		line-height: 24rpx;
		font-weight: normal;
		margin-top: 4rpx;
	}

	.tui-pro-titbox {
		font-size: 32rpx;
		font-weight: 500;
		position: relative;
		padding: 0 150rpx 20rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-pro-title {
		padding-top: 20rpx;
	}

	.tui-share-btn {
		display: block;
		background: transparent;
		margin: 0;
		padding: 0;
		border-radius: 0;
		border: 0;
	}

	.tui-share-btn::after {
		border: 0;
	}

	.tui-share-box {
		display: flex;
		align-items: center;
	}

	.tui-share-position {
		position: absolute;
		right: 0;
		top: 30rpx;
	}

	.tui-share-text {
		padding-left: 8rpx;
	}

	.tui-sub-title {
		padding: 20rpx 0;
		line-height: 32rpx;
	}

	.tui-sale-info {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding-top: 30rpx;
	}

	.tui-discount-box {
		background: #fff;
	}

	.tui-list-cell {
		width: 100%;
		position: relative;
		display: flex;
		align-items: center;
		font-size: 26rpx;
		line-height: 26rpx;
		padding: 36rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-right {
		position: absolute;
		right: 30rpx;
		top: 30rpx;
	}

	.tui-top40 {
		top: 40rpx !important;
	}

	.tui-bold {
		font-weight: bold;
	}

	.tui-list-cell::after {
		content: '';
		position: absolute;
		border-bottom: 1rpx solid #eaeef1;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
		bottom: 0;
		right: 0;
		left: 126rpx;
	}

	.tui-last::after {
		border-bottom: 0 !important;
	}

	.tui-flex-center {
		display: flex;
		align-items: center;
	}


	.tui-cell-title {

		padding-right: 30rpx;
		flex-shrink: 0;
	}

	.tui-promotion-box {
		display: flex;
		align-items: center;
		padding: 10rpx 0;
		width: 80%;
	}

	.tui-promotion-box text {
		width: 70%;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.tui-basic-info {
		background: #fff;
	}

	.tui-addr-box {
		width: 76%;
	}

	.tui-addr-item {
		padding: 10rpx;
		line-height: 34rpx;
	}

	.tui-guarantee {
		background: #fdfdfd;
		display: flex;
		flex-wrap: wrap;
		padding: 20rpx 30rpx 30rpx 30rpx;
		font-size: 24rpx;
	}

	.tui-guarantee-item {
		color: #999;
		padding-right: 30rpx;
		padding-top: 10rpx;
	}

	.tui-pl {
		padding-left: 4rpx;
	}

	.tui-cmt-box {
		background: #fff;
	}

	.tui-between {
		justify-content: space-between !important;
	}

	.tui-cmt-all {
		color: #ff201f;
		padding-right: 8rpx;
	}

	.tui-cmt-content {
		font-size: 26rpx;
	}

	.tui-cmt-user {
		display: flex;
		align-items: center;
	}

	.tui-acatar {
		width: 60rpx;
		height: 60rpx;
		border-radius: 30rpx;
		display: block;
		margin-right: 16rpx;
	}

	.tui-cmt {
		padding: 14rpx 0;
	}

	.tui-attr {
		font-size: 24rpx;
		color: #999;
		padding: 6rpx 0;
	}

	.tui-cmt-btn {
		padding: 50rpx 0 30rpx 0;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-nomore-box {}

	.tui-product-img {
		display: flex;
		flex-direction: column;
		transform: translateZ(0);
	}

	.tui-product-img image {
		width: 100%;
		display: block;
	}

	/*底部操作栏*/

	.tui-col-7 {
		width: 58.33333333%;
	}

	.tui-col-5 {
		width: 41.66666667%;
	}

	.tui-operation {
		width: 100%;

		height: 110rpx;
		background: rgba(255, 255, 255, 0.98);
		position: fixed;
		display: flex;
		align-items: center;
		justify-content: space-between;
		z-index: 10;
		bottom: 0;
		left: 0;
		padding-bottom: env(safe-area-inset-bottom);
	}

	.tui-safearea-bottom {
		width: 100%;
		height: env(safe-area-inset-bottom);
	}

	.tui-operation::before {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		left: 0;
		border-top: 1rpx solid #eaeef1;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
	}

	.tui-operation-left {
		display: flex;
		align-items: center;
	}

	.tui-operation-item {
		flex: 1;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		position: relative;
	}

	.tui-operation-text {
		font-size: 22rpx;
		color: #333;
	}

	.tui-opacity {
		opacity: 0.5;
	}

	.tui-scale-small {
		transform: scale(0.9);
		transform-origin: center center;
	}

	.tui-operation-right {
		height: 100rpx;
		padding-top: 0;
	}

	.tui-right-flex {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-flex-1 {
		flex: 1;
		padding: 16rpx;
	}

	/*底部操作栏*/

	/*底部选择弹层*/

	.tui-popup-class {
		border-top-left-radius: 24rpx;
		border-top-right-radius: 24rpx;
		padding-bottom: env(safe-area-inset-bottom);
	}

	.tui-popup-box {
		position: relative;
		padding: 30rpx 0 100rpx 0;
	}

	.tui-popup-btn {
		width: 100%;
		position: absolute;
		left: 0;
		bottom: 0;
	}

	/* .tui-popup-btn .tui-btn-class {
		width: 90% !important;
		display: block !important;
		font-size: 28rpx !important;
	} */

	/* .tui-icon-close {
		position: absolute;
		top: 30rpx;
		right: 30rpx;
	} */

	.tui-product-box {
		display: flex;
		align-items: flex-end;
		font-size: 24rpx;
		padding-bottom: 30rpx;
	}

	.tui-popup-img {
		height: 200rpx;
		width: 200rpx;
		border-radius: 24rpx;
		display: block;
	}

	.tui-popup-price {
		padding-left: 20rpx;
		padding-bottom: 8rpx;
	}

	.tui-amount {
		color: #ff201f;
		font-size: 36rpx;
	}

	.tui-number {
		font-size: 24rpx;
		line-height: 24rpx;
		padding-top: 12rpx;
		color: #999;
	}

	.tui-popup-scroll {
		height: 600rpx;
		font-size: 26rpx;
	}

	.tui-scrollview-box {
		padding: 0 30rpx 60rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-attr-title {
		padding: 10rpx 0;
		color: #333;
	}

	.tui-attr-box {
		font-size: 0;
		padding: 20rpx 0;
	}

	.tui-attr-item {
		max-width: 100%;
		min-width: 200rpx;
		height: 64rpx;
		display: -webkit-inline-flex;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: #f7f7f7;
		padding: 0 26rpx;
		box-sizing: border-box;
		border-radius: 32rpx;
		margin-right: 20rpx;
		margin-bottom: 20rpx;
		font-size: 26rpx;
	}

	.tui-attr-active {
		background: #fcedea !important;
		color: #e41f19;
		font-weight: bold;
		position: relative;
	}

	.tui-attr-active::after {
		content: '';
		position: absolute;
		border: 1rpx solid #e41f19;
		width: 100%;
		height: 100%;
		border-radius: 40rpx;
		left: 0;
		top: 0;
	}

	.tui-number-box {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 20rpx 0 30rpx 0;
		box-sizing: border-box;
	}

	.share {
		position: fixed;
		color: #FFFFFF;
		right: 0;
		bottom: 190rpx;
		background: linear-gradient(to bottom right, #FE726B, #FE956B);
		padding: 10rpx 10rpx 10rpx 20rpx;
		border-top-left-radius: 50px;
		border-bottom-left-radius: 50px;
		box-shadow: 0 0 20upx rgba(0, 0, 0, .09);
	}

	.cancel {
		width: 100vw;
		padding: 30rpx;
		text-align: center;
		background: #FFFFFF;
		color: red;
		font-weight: bold;
		font-size: 30rpx;
	}



	/*底部选择弹层*/
</style>
