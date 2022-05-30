<template>
	<div>
		<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
			<icon class="el-icon-upload"></icon>
		</div>
	</div>
</template>

<script>
	export default {


	}
</script>

<style lang="scss">
	.add-image-btn {
		position: relative;
		width: 100px;
		height: 100px;
		color: #419EFB;
		border: 1px solid #e2e2e2;
		cursor: pointer;
		padding: 2px;
		margin-right: 10px;
		font-size: 45px;

		.pic-del {
			position: absolute;
			top: -10px;
			height: 20px;
			width: 20px;
			background-color: #F56C6C;
			border-radius: 50%;
			color: #FFF0FF;
			right: -10px;
			font-size: 12px;
		}
	}
</style>
