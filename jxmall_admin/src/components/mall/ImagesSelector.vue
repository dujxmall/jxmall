<template>
	<div class="flex-row">
		<template v-if="pic_list&&pic_list.length">
			<div class="flex-row">
				<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
					v-for="(item,index) in pic_list">
					<el-image :src="item" fit="cover" style="width: 100%;height: 100%;"></el-image>
					<div class="flex-x-center flex-y-center pic-del" @click="deletePic(index)">
						<i class="el-icon-close"></i>
					</div>
				</div>
			</div>
		</template>
		<file-picker :max="count" :multiple='true' @selected="chooseImage" v-if="pic_list&&pic_list.length<count">
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
			count: {
				type: Number,
				default: 0
			},
			size: {
				type: String,
				default: '768*768'
			},
			list: {
				type: Array,
				default: () => [],
			},
		},
		data() {
			return {
				pic_url: '',
				pic_list: [],
			}
		},
		watch: {
			list(newVal, oldVal) {
				if (newVal != oldVal) {
					this.pic_list = newVal;
				}
			},
		},

		methods: {
			deletePic(index) {
				this.pic_list.splice(index, 1);
				this.$emit('input', this.pic_list);
			},
			deleteImage() {
				this.pic_url = '';
				this.$emit('input', this.pic_url);
			},
			chooseImage(e) {
				if (this.pic_list.length >= this.count) {
					return;
				}
				e.forEach(item => {
					this.pic_list.push(item.url)
				})
				this.$emit('input', this.pic_list);
			},
		}
	}
</script>


<style>
</style>
