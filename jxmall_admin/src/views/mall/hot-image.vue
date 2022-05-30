<template>
<div class="app-container">
	<el-card class="box-card" v-loading="is_loading">
			<div slot="header" class="clearfix">
				<span>热图编辑</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
			</div>
			<div class="layout">
				<div class="center" style="padding-left: 20px;">
					<OperationFloor :value="form" />
				</div>
				<el-form label-width="100px">
					<el-form-item label="背景图">
						<el-input placeholder="热图名称" style="width: 300px;" v-model="form.name"></el-input>
					</el-form-item>
					<el-form-item label="背景图">
						<div>
							<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
								v-if="form.pic_url">
								<img :src="form.pic_url" alt="" style="width: 100%;height: 100%;">
								<span class="flex-x-center flex-y-center pic-del" @click="form.pic_url=''">
									<i class="el-icon-close"></i></span>
							</div>
							<file-picker v-if="!form.pic_url" style="margin-right: 5px;" v-model="form.pic_url" width="88"
								height="88">
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
									<i class="el-icon-upload"></i>
								</div>
							</file-picker>
						</div>
					</el-form-item>
				</el-form>
			</div>
		</el-card>
	
</div>
</template>
<script>
	import OperationFloor from '@/components/diy/HotImage/OperationFloor'
	export default {
		name: 'hot-image',
		components: {
			OperationFloor
		},
		data() {
			return {
				is_loading: false,
				form: {
					id: '',
					name: '',
					data: [],
					pic_url: '',
				}
			}
		},
		created() {
			if (this.$route.query.id) {
				this.form.id = this.$route.query.id;
				this.search();
			}
		},
		methods: {
			save() {
				this.is_loading = true;
				this.$request({
					url: "/mall/mall/hot-image",
					data: this.form,
					method: 'post'
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.$message.success(res.msg);
						this.$go.back();
					}
				}).catch(e => {
					this.is_loading = false;
				})
			},
			search() {
				this.is_loading = true;
				this.$request({
					url: "/mall/mall/hot-image",
					data: this.form,
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.form = Object.assign(this.form,res.data.info);
						this.$forceUpdate();
					}
				}).catch(e => {
					this.is_loading = false;
				})

			},
		}
	}
</script>

<style scoped lang="scss" ref="stylesheet/scss">
	.layout {
		width: 100%;

		display: grid;
		grid-template-columns: 1fr 1000px 1fr;

		.top {
			grid-column-start: 1;
			grid-column-end: 4;
			height: 200px;

		}
	}
</style>
