<template>
	<view class="rich-content">
		<WxParse :content="value"></WxParse>
	</view>
</template>

<script>
	import WxParse from '@/components/uni/uParse/src/wxParse'
	export default {
		components: {
			WxParse
		},
		props: {
			value: {
				type: String,
				default: ''
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
	.rich-content img{
		width: 100%;
	}
	.rich-content{
	 
	}

</style>
