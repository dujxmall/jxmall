<template>
	<view style="width: 100%;" :style="{height:value.height+'rpx'}">


	</view>
</template>

<script>
	export default {
		props: {
			value: {
				type: Object,
				default: () => {
					return {
						height: 2
					};
				}
			}
		},
		data() {
			return {

			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
