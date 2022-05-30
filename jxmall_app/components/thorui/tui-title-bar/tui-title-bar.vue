<template>
	<view>
		<view style="height: 80rpx;padding: 0 20rpx;box-sizing: border-box;"
			:style="{background:value.background?value.background:'#ffffff',color:value.title_color}"
			class="flex-x-between" @click="pageTo">
			<view style="font-size: 16px;font-weight: bold">{{value.title}}</view>
			<view class="flex-y-center" style="font-size: 11pt;">
				<text>{{value.sub_title}}</text>
				<tui-icon name="arrowright" :size='20'></tui-icon>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			value: {
				type: Object,
				default: () => {
					return {
						background: '#ffffff',
						title: '标题',
						sub_title: '副标题'
					};
				}

			}
		},
		data() {
			return {

			}
		},
		methods: {
			pageTo() {
				if (this.value.type == 1) {
					this.$T.go('/pages/web/web?url=' + this.value.url)
				} else {
					this.$T.go(this.value.url)
				}
			}
		}
	}
</script>

<style>

</style>
