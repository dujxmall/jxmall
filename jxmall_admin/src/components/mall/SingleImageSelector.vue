<template>
	<div>
		<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center" v-if="pic_url">
			<el-image :src="pic_url" fit="cover" style="width: 100%;height: 100%;"></el-image>
			<span class="flex-x-center flex-y-center pic-del" @click="deleteImage">
				<i class="el-icon-close"></i></span>
		</div>
		<file-picker :multiple='false' :max="1" @selected="chooseImage" v-if="pic_url==''">
			<div flex="main:center cross:center" style="position: relative;"
				class="add-image-btn flex-x-center flex-y-center">
				<i class="el-icon-upload"></i>
				<span style="position: absolute;top: 0px;right: 1px;font-size: 5px;color: #909399;">{{size}}</span>
			</div>
		</file-picker>
	</div>
</template>

<script>
	export default {
		props: {
			url: {
				type: String,
				default: ''
			},
			size: {
				type: String,
				default: '768*768'
			}
		},
		data() {
			return {
				pic_url: '',
			}
		},
		watch: {
			url(newVal, oldVal) {
				if (newVal != oldVal) {
					this.pic_url = newVal;
				}
			},
		},

		methods: {
			deleteImage() {
				this.pic_url = '';
				this.$emit('input', this.pic_url);
			},
			chooseImage(e) {
				this.pic_url = e[0].url;
				this.$emit('input', this.pic_url);
			},
		}
	}
</script>


<style>
</style>
