<template>

	<view class="tui-modal__container" :class="[show ? 'tui-modal-show' : '']" :style="{zIndex:zIndex}"
		@touchmove.stop.prevent>
		<view class="tui-modal-box"
			:style="{ width: width,height: height, padding: padding, borderRadius: radius, zIndex:zIndex+1 }"
			:class="[fadeIn || show ? 'tui-modal-normal' : 'tui-modal-scale', show ? 'tui-modal-show' : '']">
			<view style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: 99999999999;">
				<view style="width: 100%;height: 100%;border-radius: 20rpx;overflow: hidden;" class="flex-x-center">
					<view style="width: 90%;height: 80%;">
						<image :src="url" @click="preview" mode="aspectFill" style="width:100%;border-radius: 20rpx;height: 100%;">
						</image>
						<view class="flex-x-center" style="width: 100%;z-index: 9999999999999;" v-if="url">
							<view>
								<!-- #ifndef H5 -->
								<view class="flex-row">
									<view
										style="color: #FFFFFF;color: #FFFFFF;font-size: 10pt;border-radius: 40rpx;height: 60rpx;padding: 0rpx 20rpx;border: solid #FFFFFF 1rpx;width: 100rpx;"
										class="flex-y-center flex-x-center">
										<text @click="save">保存</text>
									</view>
									<view class="" style="width: 20rpx;height: 20rpx;">

									</view>
									<view
										style="background-color:#fff;font-size: 10pt;border-radius: 40rpx;height: 60rpx;padding: 0rpx 20rpx;width: 100rpx;"
										class="flex-y-center flex-x-center">
										<text @click="preview">预览</text>
									</view>
								</view>
								<!-- #endif -->

								<!-- #ifdef H5 -->
								<view
									style="color: #FFFFFF;color: #FFFFFF;font-size: 10pt;border-radius: 40rpx;height: 60rpx;padding: 20rpx;border: solid #FFFFFF 1rpx;"
									class="flex-y-center">
									<text style="">长按图片保存</text>
								</view>
								<!-- #endif -->
								<view class="flex-x-center" style="width: 100%;margin-top: 20rpx;" @tap.stop="close">
									<tui-icon :name="'close'" :color="'#ffff'" :size="20"></tui-icon>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>


		</view>

	</view>

</template>

<script>
	export default {
		props: {
			url: {
				type: String,
				default: ''

			},
			showPoster: {
				type: Boolean,
				default: false
			},
			width: {
				type: String,
				default: '84%'
			},
			height: {
				type: String,
				default: '84%'
			},

			padding: {
				type: String,
				default: '40rpx 64rpx'
			},
			radius: {
				type: String,
				default: '24rpx'
			},
			//标题
			title: {
				type: String,
				default: ''
			},
			//内容
			content: {
				type: String,
				default: ''
			},
			//内容字体颜色
			color: {
				type: String,
				default: '#999'
			},
			//内容字体大小 rpx
			size: {
				type: Number,
				default: 28
			},
			//点击遮罩 是否可关闭
			maskClosable: {
				type: Boolean,
				default: true
			},
			//淡入效果，自定义弹框插入input输入框时传true
			fadeIn: {
				type: Boolean,
				default: false
			},
			//自定义弹窗内容
			custom: {
				type: Boolean,
				default: false
			},
			//容器z-index
			zIndex: {
				type: Number,
				default: 9997
			},
			//mask z-index
			maskZIndex: {
				type: Number,
				default: 9990
			}
		},
		data() {
			return {
				show: false
			}
		},
		watch: {
			showPoster(newVal, oldVal) {
				if (newVal) {
					this.show = true
				}
			}
		},
		methods: {
			preview() {
				uni.previewImage({
					urls: [this.url],
					current:this.url
				})

			},
			close() {
				this.show = false;
				this.$emit('closePoster')
			},
			save() {

				uni.showLoading({
					title: '正在保存'
				})
				uni.downloadFile({
					url: this.url,
					success: function(e) {


						uni.saveImageToPhotosAlbum({
							filePath: e.tempFilePath,
							success: function(e) {
								uni.showLoading({
									title: '保存成功'
								})
								setTimeout(function() {
									uni.hideLoading()
								}, 1000)

							}
						})

					},
					fail: function(e) {
						uni.hideLoading();
						uni.showToast({
							title: '保存失败'
						})
					}
				})

			},
		}
	}
</script>

<style>
	.tui-modal__container {
		width: 100%;
		height: 100%;
		position: fixed;
		left: 0;
		top: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		visibility: hidden;
		background: rgba(0, 0, 0, 0.3);
	}

	.tui-modal-box {
		position: relative;
		opacity: 0;
		visibility: hidden;
		box-sizing: border-box;
		transition: all 0.3s ease-in-out;
	}

	.tui-modal-scale {
		transform: scale(0);
	}

	.tui-modal-normal {
		transform: scale(1);
	}

	.tui-modal-show {
		opacity: 1;
		visibility: visible;
	}

	.tui-modal-mask {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0, 0, 0, 0.6);
		transition: all 0.3s ease-in-out;
		opacity: 0;
		visibility: hidden;
	}

	.tui-mask-show {
		visibility: visible;
		opacity: 1;
	}

	.tui-modal-title {
		text-align: center;
		font-size: 34rpx;
		color: #333;
		padding-top: 20rpx;
		font-weight: bold;
	}

	.tui-modal-content {
		text-align: center;
		color: #999;
		font-size: 28rpx;
		padding-top: 20rpx;
		padding-bottom: 60rpx;
	}

	.tui-mtop {
		margin-top: 30rpx;
	}

	.tui-mbtm {
		margin-bottom: 30rpx;
	}

	.tui-modalBtn-box {
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.tui-flex-column {
		flex-direction: column;
	}

	.tui-modal-btn {
		width: 46%;
		height: 68rpx;
		line-height: 68rpx;
		position: relative;
		border-radius: 10rpx;
		font-size: 26rpx;
		overflow: visible;
		margin-left: 0;
		margin-right: 0;
	}

	.tui-modal-btn::after {
		content: ' ';
		position: absolute;
		width: 200%;
		height: 200%;
		-webkit-transform-origin: 0 0;
		transform-origin: 0 0;
		transform: scale(0.5, 0.5) translateZ(0);
		left: 0;
		top: 0;
		border-radius: 20rpx;
		z-index: 2;
	}

	.tui-btn-width {
		width: 80% !important;
	}

	.tui-primary {
		background: #5677fc;
		color: #fff;
	}

	.tui-primary-hover {
		background: #4a67d6;
		color: #e5e5e5;
	}

	.tui-primary-outline {
		color: #5677fc;
		background: transparent;
	}

	.tui-primary-outline::after {
		border: 1px solid #5677fc;
	}

	.tui-danger {
		background: #ed3f14;
		color: #fff;
	}

	.tui-danger-hover {
		background: #d53912;
		color: #e5e5e5;
	}

	.tui-danger-outline {
		color: #ed3f14;
		background: transparent;
	}

	.tui-danger-outline::after {
		border: 1px solid #ed3f14;
	}

	.tui-red {
		background: #e41f19;
		color: #fff;
	}

	.tui-red-hover {
		background: #c51a15;
		color: #e5e5e5;
	}

	.tui-red-outline {
		color: #e41f19;
		background: transparent;
	}

	.tui-red-outline::after {
		border: 1px solid #e41f19;
	}

	.tui-warning {
		background: #ff7900;
		color: #fff;
	}

	.tui-warning-hover {
		background: #e56d00;
		color: #e5e5e5;
	}

	.tui-warning-outline {
		color: #ff7900;
		background: transparent;
	}

	.tui-warning-outline::after {
		border: 1px solid #ff7900;
	}

	.tui-green {
		background: #19be6b;
		color: #fff;
	}

	.tui-green-hover {
		background: #16ab60;
		color: #e5e5e5;
	}

	.tui-green-outline {
		color: #19be6b;
		background: transparent;
	}

	.tui-green-outline::after {
		border: 1px solid #19be6b;
	}

	.tui-white {

		color: #333;
	}

	.tui-white-hover {
		background: #f7f7f9;
		color: #666;
	}

	.tui-white-outline {
		color: #333;
		background: transparent;
	}

	.tui-white-outline::after {
		border: 1px solid #333;
	}

	.tui-gray {
		background: #ededed;
		color: #999;
	}

	.tui-gray-hover {
		background: #d5d5d5;
		color: #898989;
	}

	.tui-gray-outline {
		color: #999;
		background: transparent;
	}

	.tui-gray-outline::after {
		border: 1px solid #999;
	}

	.tui-outline-hover {
		opacity: 0.6;
	}

	.tui-circle-btn {
		border-radius: 40rpx !important;
	}

	.tui-circle-btn::after {
		border-radius: 80rpx !important;
	}
</style>
