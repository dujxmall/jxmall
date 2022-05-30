<template>
	<view>

	<!-- 	<rich-text style="white-space: pre-wrap;" :nodes="value"></rich-text> -->
		<u-parse style="white-space: pre-wrap;" :content="value"></u-parse>
	</view>
</template>
<script>
	import uParse from "@/components/feng-parse/parse.vue"

	export default {
		components: {
			uParse
		},
		props: {
			value: {
				type: String,
				default: ''
			}
		},
		data() {
			return {

			};
		},

		methods: {

		}
	}
</script>
<style>
	.p {
		display: block !important;

	}

	img {
		width: 100%;
	}

	view {
		font-size: 28upx;
	}

	.grace-rich-text {}
</style>
