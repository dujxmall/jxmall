<template>
	<view>
		<tui-fab v-if="value.type==1" :radius="value.radius" :width="value.width" :height="value.height" :left="0"
			:right="value.right" :bottom="value.bottom" :bgColor="value.bgColor" :btnList="value.list" @click="onClick">
		</tui-fab>
		<view style="position: fixed;" :style="buttonStyle" v-else>
			<tui-button :bgColor="item.bgColor" :openType="item.openType" @click="onClick({index:index})"
				:width="item.imgWidth+'rpx'" :height="item.imgHeight+'rpx'" v-if="value.list.length>0"
				:shape="value.radius==0?'square':'circle'" v-for="(item,index) in value.list"
				class="flex-y-center flex-x-center" style="position: relative;">
				<image :src="item.imgUrl" mode="aspectFill" style="width: 100%;height: 100%;"
					:style="{borderRadius:value.radius}"></image>
				<view style="position: absolute;top: 0;left: -100rpx;text-align: end;width: 100%;"
					class="flex-y-center flex-x-center" v-if="item.text"
					:style="{color:item.color,fontSize:item.fontSize+'rpx'}">
					{{item.text}}
				</view>
			</tui-button>
		</view>
	</view>
</template>

</template>

<script>
	export default {
		name: 'tui-float-button',
		props: {
			value: {
				type: Object,
				default: () => {
					return {
						bottom: 100,
						right: 20,
						list: [],
						type: 0, //0展开类型  1 收缩类型
						bgColor: '#ffff',
					};
				}
			}
		},
		data() {
			return {

			}
		},
		created() {
			console.log(this.value);
		},
		computed: {
			buttonStyle() {
				return `bottom:${this.value.bottom}rpx;right:${this.value.right}rpx`;
			},
		},
		methods: {
			onClick(e) {
				const button = this.value.list[e.index];
				if (button.openType == 'nav') {
					this.$T.go(button.value);
				}
				if (button.openType == 'call') {
					this.$util.call(button.value);
				}
				if (button.openType == 'location') {
					this.$util.openLocation(button.value, button.lat, button.lon);
				}
			},
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
