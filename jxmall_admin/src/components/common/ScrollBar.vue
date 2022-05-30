<template>
	<el-scrollbar :style="{'height':height+'px'}">
		<slot></slot>
	</el-scrollbar>
</template>

<script>
	export default {
		name: 'scorll-bar',
		props: {
			height: {
				type: Number,
				default: 750
			}
		}
	}
</script>

<style scoped>
	.el-scrollbar__wrap {
		overflow-x: hidden;
	}

	.scrollbar-wrapper {
		overflow-x: hidden !important;
	}

	.el-scrollbar__bar.is-vertical {
		right: 0px;
	}

	::-webkit-scrollbar {
		/* display: none */
	}

	.el-scrollbar {
		height: 100%;
	}
</style>
