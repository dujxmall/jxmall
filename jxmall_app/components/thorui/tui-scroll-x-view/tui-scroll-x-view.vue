<template>
	<view>
		<scroll-view scroll-x="true" style="display: flex;white-space: nowrap;">
			<view style="display: inline-block;">
				<slot></slot>
			</view>
		</scroll-view>

	</view>
</template>

<script>
	export default {
		name: 'tuiScrollXView',
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
