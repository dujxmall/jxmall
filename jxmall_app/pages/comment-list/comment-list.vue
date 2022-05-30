<!-- components/evaluate-components/index.wxml -->
<template>
	<tui-page>
		<view class="evaluateBox" style="background-color: #FFFFFF;">
			<view class="evaluate-header">
				<view class="title">
					<text v-if="isShowIcon" class="iconfont" style="color:#999">&#xe640;</text>
					<text v-if="isShowTotal" style="margin-left: 10upx;">评价 ({{ total }})</text>
				</view>
				<view class="total">
					<view class="stars flex-y-center">
						<tui-rate :current="sum"></tui-rate>
					</view>
					<text>{{sum}} 分</text>
				</view>
			</view>
		
			<view class="lists" v-if="list.length > 0">
				<block v-for="(item, index_) in list" :key="index_">
					<view class="item">
						<view class="icon" style="width:80rpx;height: 80rpx;border-radius: 50%;">
							<image :src="item.avatar_url" mode="aspectFill" style="width: 100%;height: 100%;" />
						</view>
						<view class="info">
							<view class="name-time">
								<text class="name">{{ item.nickname }}</text>
								<view class="time" style="font-size: 10pt;">{{ item.created_at }}</view>
							</view>
							<view class="stars">
								<tui-rate :current="item.grade_level"></tui-rate>
							</view>
							<view class="evaluate-content">
								<text>{{ item.content || '用户暂未评价' }}</text>
								<view class="imgs" v-if="item.pic_list">
									<block v-for="(imgurl, index) in item.pic_list" :key="index">
										<view class="imgs-box">
											<image :src="imgurl" mode="widthFix" style="width: 100%;" @click="previewImg(imgurl,item.pic_list)"></image>
										</view>
									</block>
								</view>
							</view>
						</view>
					</view>
				</block>
			</view>
			<view class="no-lists" v-else>暂无评论</view>
		</view>
		
	</tui-page>
</template>

<script>
	//数据模拟

	export default {
		props: {
			//评价列表数据

			//是否显示总评价数量
			isShowTotal: {
				type: Boolean,
				default: true
			},
			//是否显示评价前面的图标
			isShowIcon: {
				type: Boolean,
				default: true
			},
			//总评分
			rate: {
				type: Number,
				default: 4.6
			}
		},
		data() {
			return {
				page: 1,
				listData: [{
						header_img: "https://vkceyugu.cdn.bspapp.com/VKCEYUGU-ask-img/1bbd9b60-09df-11eb-8a36-ebb87efcf8c0.jpg",
						user_name: "测试1",
						rate: 5,
						create_time: "2019-04-12",
						content: "好评",
						imgs: [
							'https://vkceyugu.cdn.bspapp.com/VKCEYUGU-ask-img/1bbd9b60-09df-11eb-8a36-ebb87efcf8c0.jpg',

						]
					},
					{
						content: "中评",
						create_time: "2019-04-12",
						header_img: "https://vkceyugu.cdn.bspapp.com/VKCEYUGU-ask-img/1bbd9b60-09df-11eb-8a36-ebb87efcf8c0.jpg",
						user_name: "测试2",
						rate: 4,
						// imgs:[]
					},
					{
						content: "",
						create_time: "2019-04-12",
						header_img: 'https://vkceyugu.cdn.bspapp.com/VKCEYUGU-ask-img/1bbd9b60-09df-11eb-8a36-ebb87efcf8c0.jpg',

						user_name: "测试3",
						rate: 2,
						// imgs:[]
					}, {
						content: "好评",
						create_time: "2019-04-12",
						header_img: "http://cs.zhangkaixing.com/face/face_2.jpg",
						user_name: "测试1",
						rate: 5,
						imgs: [
							'https://vkceyugu.cdn.bspapp.com/VKCEYUGU-ask-img/1bbd9b60-09df-11eb-8a36-ebb87efcf8c0.jpg',

						]
					},
					{
						content: "中评",
						create_time: "2019-04-12",
						header_img: 'https://vkceyugu.cdn.bspapp.com/VKCEYUGU-ask-img/1bbd9b60-09df-11eb-8a36-ebb87efcf8c0.jpg',

						user_name: "测试2",
						rate: 3.5,
						// imgs:[]
					},
					{
						content: "",
						create_time: "2019-04-12",
						header_img: 'https://vkceyugu.cdn.bspapp.com/VKCEYUGU-ask-img/1bbd9b60-09df-11eb-8a36-ebb87efcf8c0.jpg',
						user_name: "测试3",
						rate: 2.3,
						// imgs:[]
					}
				],
				list: [],
				goods_id: '',
				sum: 0,
				total: 0,
				is_no_more: false,
			}
		},
		onLoad: function(options) {
			if (options.goods_id) {
				this.goods_id = options.goods_id;
			} else {
				uni.showModal({
					title: '提示',
					content: '商品不存在',
					success() {
						uni.navigateBack({
							delta: 1
						})
					}
				})
				return;
			}
			this.getList();
		},
		methods: {
			previewImg(src, urls) {
				uni.previewImage({
					current: src,
					urls
				})
			},
			getList() {
				if (this.is_no_more) {
					uni.showToast({
						title: '没有更多数据'
					})
					return;
				}
				this.$request({
					url: '/api/goods/comment-list',
					data: {
						goods_id: this.goods_id,
						page: this.page
					}
				}).then((res) => {

					if (res.code == 0) {

						this.sum = res.data.sum;
						this.total = res.data.total;

						for (let i in res.data.list) {
							let item = res.data.list[i];
							item.pic_list = JSON.parse(item.pic_list);
						}
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
					}

				})
			},
		}
	};
</script>
<style scoped>
	@font-face {
		font-family: 'iconfont';
		/* project id 1237225 */
		src: url('https://at.alicdn.com/t/font_1237225_y90nldmnpij.eot');
		src: url('https://at.alicdn.com/t/font_1237225_y90nldmnpij.eot?#iefix') format('embedded-opentype'),
			url('https://at.alicdn.com/t/font_1237225_y90nldmnpij.woff2') format('woff2'),
			url('https://at.alicdn.com/t/font_1237225_y90nldmnpij.woff') format('woff'),
			url('https://at.alicdn.com/t/font_1237225_y90nldmnpij.ttf') format('truetype'),
			url('https://at.alicdn.com/t/font_1237225_y90nldmnpij.svg#iconfont') format('svg');
	}

	.iconfont {
		font-family: "iconfont" !important;
		font-size: 16px;
		font-style: normal;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}

	.icon-pingjia-copy:before {
		content: "\e640";
	}

	.icon-xingxing:before {
		content: "\e870";
	}



	.evaluateBox {
		width: 100%;
		margin-bottom: 120upx;
	}

	.evaluate-header {
		width: 100%;
		height: 80upx;
		display: flex;
		justify-content: space-between;
		padding: 20upx;
		align-items: center;
		margin-bottom: 12upx;
		box-sizing: border-box;
		border-bottom: 1upx solid #e5e5e5;
	}

	.evaluateBox .title {
		display: flex;
		align-items: center;
		color: #000000;
		font-size: 30upx;
	}

	.total {
		/* flex: 1; */
		height: 100%;
		font-size: 30upx;
		color: #d76d9d;
		display: flex;
		align-items: center;
		justify-content: flex-end;
	}

	.stars {
		width: 180upx;
		height: 36upx;
		position: relative;
		margin-right: 20rpx;
	}

	.stars .box {
		width: 180upx;
	}

	.stars-normal {
		width: 100%;
		position: absolute;
		left: 0;
		top: 0;
		color: #ccc;
	}

	.stars-selected {
		position: absolute;
		left: 0;
		top: 0;
		z-index: 1;
		color: #fde16d;
		overflow: hidden;
	}

	.stars-selected .iconfont,
	.stars-normal .iconfont {
		font-size: 36upx;
	}

	.lists .item {
		padding: 20upx;
		display: flex;
		/* height: auto; */
		/* align-items: center; */
		font-size: 22upx;
		color: #999;
	}

	.lists .item .icon {
		width: 60upx;
		height: 60upx;
		border-radius: 50%;
		overflow: hidden;
		margin-right: 26upx;
		/* align-self: flex-start; */
	}

	.lists .item .info {
		flex: 1;
		font-size: 26upx;
		color: #666;
	}

	.info .name-time {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding-bottom: 6upx;
	}

	.lists .info .stars {
		width: 140upx;
	}

	.info .stars-normal .iconfont,
	.info .stars-selected .iconfont {
		font-size: 28upx;
	}

	.info .stars .box {
		width: 140upx;
	}

	.lists .info .evaluate-content {
		color: #000;
		font-size: 28upx;
		text-align: left;
		padding-top: 6upx;
	}

	.info .evaluate-content .imgs {
		display: flex;
		flex-wrap: wrap;
		padding-top: 6upx;
	}

	.evaluate-content .imgs .imgs-box {
		width: 25%;
		padding-right: 10upx;
		box-sizing: border-box;
	}

	/* .evaluate-content .imgs .imgs-box:nth-child(4n+1){
	padding-left: 0;
}
.evaluate-content .imgs .imgs-box:nth-child(4n){
	padding-right: 0;
} */
	.no-lists {
		padding: 20upx 0;
		text-align: center;
		font-size: 24upx;
		color: #666;
	}
</style>
