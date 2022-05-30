<template>
	<view>
		<tui-modal-container :show="visible" :width="'100%'" :height="'100%'">
			<view style="width: 100%;height: 100%;position: relative;">
				<view class="img-container">
					<view style="position: relative;text-align: center;width: 100%;height: 100%;">
						<view style="position: absolute;width: 100%;height: 100%;":style="{top:value.bg.top+'%'}">
							<image :src="value.bg.url" style="margin: 0 auto;overflow: hidden;" :style="{height:value.bg.height+'%',borderRadius:value.bg.radius+'rpx',width:value.bg.width+'%'}"
							 mode="scaleToFill"></image>
						</view>
					</view>
				</view>
				<view style="z-index: 9999999999;position: absolute;width: 100%;left: 0;" :style="{top:value.btn.top+'%'}" class="flex-y-center flex-x-center">
					<view style="height: 100rpx;overflow: hidden;border: #f0f0f0 solid 1rpx;" :style="{borderColor:value.btn.borderColor,width:value.btn.width+'%',borderRadius:value.btn.radius+'rpx',backgroundColor:value.btn.bgColor,color:value.btn.color,fontSize:value.btn.fontSize+'rpx',height:value.btn.height+'rpx'}"
					 class="flex-y-center flex-x-center" v-if="value.btn.style==0" @click="btnClick">
						{{value.btn.text}}
					</view>
					<image :src="value.btn.btnImg" :style="{width:value.btn.width,borderRadius:value.btn.radius+'rpx',height:value.btn.height+'rpx'}"
					 mode="scaleToFill" v-if="value.btn.style==1" @click="btnClick"></image>
				</view>
			</view>
		</tui-modal-container>
	</view>
</template>

<script>
	export default {
		props: {
			show: {
				type: Boolean,
				default: false
			},
			value: {
				type: Object,
				default: () => {
					return {
						bg: {
							top: '10',
							height: '70',
							width: '80',
							url: '',
							radius: 30
						},
						btn: {
							type: 0, //0 close 跳转按钮
							top: '60',
							width: '60',
							text: '关闭',
							radius: '60',
							height: '100',
							color: '#000',
							bgColor: '#fff',
							borderColor: '#fff',
							url: '',
							fontSize: '35',
							btnImg: '',
							style: 1, //0 普通按钮  1 图片按钮
						}
					}
				}
			}
		},

		data() {
			return {
				visible: true,
			}
		},
		methods: {
			btnClick() {
				console.log(this.value.btn.type);
				if (this.value.btn.type == 0) {
					this.visible = false;
				} else {
					this.visible = false;
					uni.navigateTo({
						url:this.value.btn.url
					})//这里跳转
				}
			}
		}
	}
</script>

<style>
	.img-container {
		width: 100%;
		position: absolute;
		left: 0%;
		top: 0%;
		height: 100%;
		border-radius: 20rpx;
		overflow: hidden;
	}
</style>
