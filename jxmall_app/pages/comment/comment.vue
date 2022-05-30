<template>
	<view class="app">
		<view class="goods">
			<view class="goods-items">
				<view class="item" v-for="(item, i) in list" :key="i">
					<view class="image-score">
						<image class="img" :src="item.cover_pic" mode="aspectFill"></image>
						<view class="score">
							<view class="desc">描述相符</view>
							<view class="star">
								<tui-rate :current="item.grade_level" :select_index="i" @change="change"></tui-rate>
							</view>
						</view>
					</view>
					<view class="text-image">
						<view class="text">
							<textarea class="textarea" v-model="item.content" maxlength="100" placeholder="商品满足你的期待吗？说说你的使用心得,分享给想买的他们吧!"></textarea>
						</view>
						<view class="upload">
							<tui-upload :serverUrl="'/api/upload/upload'" :index="i" @complete="uploadComplete"></tui-upload>
						</view>
					</view>
				</view>
			</view>
			<view class="btn submit" @click="submit">确认发布</view>
		</view>
	</view>
</template>

<script>
	export default {

		data() {
			return {
				detail: null,
				list: [],
				serverUrl: '',
				uploadId: 0,
				changeId: 0,
				order_id: 0,
				editRow: null,
			};
		},
		onLoad(options) {
			if (options.order_id) {
				this.order_id = options.order_id;

			}
			this.getDetail();
		},
		methods: {
			submit() {
				this.$request({
					url: '/api/goods/comment',
					data: {
						data_list: JSON.stringify(this.list)
					},
					method: 'post'
				}).then(res => {

					if (res.code == 0) {
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: function(e) {
								uni.navigateBack({
									delta: 1
								})
							}
						})

					} else {
						uni.showModal({
							title: '提示',
							content: res.msg
						})
					}
				})
			},
			uploadComplete(e) {

				if (e.index != -1) {
					this.list[e.index].pic_list = e.imgArr;
				}
			},

			getDetail() {
				this.$request({
					url: '/api/order/detail',
					data: {
						order_id: this.order_id
					},

				}).then(res => {
					if (res.code === 0) {
						//this.list = res.data.list
						for (let i in res.data.list) {
							let item = res.data.list[i];
							this.list.push({
								goods_id: item.goods_id,
								order_id: item.goods_id,
								cover_pic: item.goods.cover_pic,
								grade_level: 5,
								content: '',
								pic_list: [],


							})
						}
					}
				});
			},

			change(e) {
				this.list[e.select_index].grade_level = e.index;
			},
			remove(e) {
				//移除图片
				let index = e.index;
			}
		},
		computed: {
			finalData() {
				let temp = {
					order_id: 0,
					commentData: []
				};

				let {
					order_id,
					list
				} = JSON.parse(JSON.stringify(this.dataForm));
				list.forEach(v => {
					delete v.pic_url;
				});
				// console.log(list);
				temp.order_id = order_id;
				temp.commentData = list;
				return temp;
			},
			dataList() {
				let arr = {
					order_id: -1,
					list: []
				};
				arr.order_id = this.detail.id;
				this.detail.order_goods_list.forEach((item, i) => {
					let temp = {
						pic_url: item.goods_info.pic_url,
						order_detail_id: item.id,
						grade_level: 5,
						content: '',
						pic_list: []
					};
					arr.list.push(temp);
				});
				return arr;
			}
		}
	};
</script>

<style lang="scss" scoped>
	.app {
		height: 100%;
		background-color: #f7f7f7;

		.goods {
			padding: 20rpx;

			.goods-items {
				padding: 10rpx;
				color: #333333;
				font-size: 11pt;

				.item {
					padding: 30rpx 16rpx;
					background-color: #ffffff;
					border-radius: 20rpx;
					margin-bottom: 20rpx;

					.image-score {
						display: flex;
						margin-bottom: 30rpx;

						.img {
							width: 120rpx;
							height: 120rpx;
							margin-right: 16rpx;
						}

						.score {
							display: flex;
							align-items: center;

							.desc {
								margin-right: 36rpx;
							}
						}
					}

					.text-image {
						.text {
							.textarea {
								width: 100%;
								height: 140rpx;
								font-size: 10pt;
							}

							.uni-textarea-placeholder {
								color: #999999;
							}
						}

						.upload {
							margin-top: 20rpx;
							display: flex;
							position: relative;

							.upload-img {
								width: 180rpx;
								height: 180rpx;
								color: #bfbfbf;
								border: 1rpx dotted #bfbfbf;
								z-index: 10;

								.iconfont {
									font-size: 16pt;
								}
							}
						}
					}
				}
			}
		}

		.btn {
			line-height: 90rpx;
			text-align: center;
			border-radius: 45rpx;
			font-size: 9pt;
			margin: 40rpx 0;

			&.submit {
				color: #ffffff;
				background-color: #bc0100;
			}
		}
	}
</style>
