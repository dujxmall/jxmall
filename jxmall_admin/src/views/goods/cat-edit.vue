<template>

	<div class="app-container">
		<el-card class="box-card" v-loading='is_loading'>
			<div slot="header" class="clearfix">
				<span>分类编辑</span>
			</div>
			<div>
				<el-form ref="form" :model="form" label-width="180px">
					<el-row>
						<el-col :span="12">
							<el-form-item label="当前上级分类" v-if="parent_cat_name">
								<el-tag @close="closeParent" closable>
									{{parent_cat_name}}
								</el-tag>
							</el-form-item>

							<el-form-item label="选择上级分类">
								<div class="flex-y-center">

									<el-cascader @change="change" :options="cat_level_list" :show-all-levels="true"
										:props="{ multiple: false, checkStrictly: true,label:'name',value:'id' }"
										clearable>
										<template slot-scope="{ node, data }">
											<span>{{ data.name }}</span>
											<span v-if="!node.isLeaf"> ({{ data.children.length }}) </span>
										</template>
									</el-cascader>
								</div>
							</el-form-item>
							<el-form-item label="分类名称">
								<el-input v-model="form.name"></el-input>
							</el-form-item>
							<el-form-item label="排序">
								<el-input v-model="form.sort" type="number"></el-input>
								<span style="color: #C1C1C1;font-size: 10px;">数字越大，越靠前</span>
							</el-form-item>

							<el-form-item label="缩略图">
								<single-image-selector :url="form.cover_pic" v-model="form.cover_pic" size="768*768">
								</single-image-selector>
							</el-form-item>
							<el-form-item label="广告图">
								<single-image-selector :url="form.adv_pic" v-model="form.adv_pic" size="768*300">
								</single-image-selector>
							</el-form-item>

							<el-form-item label="广告链接">
								<el-tag @close="deleteLink" closable v-if="this.form.link">
									{{this.form.link}}

								</el-tag>
								<el-button type="text" @click="showLink=!showLink">选择链接</el-button>
							</el-form-item>
							<el-form-item label="是否显示">
								<el-switch v-model="form.is_show" :active-value="1" :inactive-value="0">
								</el-switch>
							</el-form-item>
						</el-col>
					</el-row>
					<el-form-item>
						<el-button type="primary" @click="submit" :loading="is_submiting">保存</el-button>
						<el-button @click="cancel">取消</el-button>
					</el-form-item>
				</el-form>
			</div>
		</el-card>
		<link-picker @selected="linkSelected" :showLink="showLink">
		</link-picker>
	</div>
</template>

<script>
	import LinkPicker from '@/components/mall/LinkPicker'
	import FilePicker from '@/components/common/FilePicker'
	export default {
		name: 'goods-cat-edit',
		components: {
			FilePicker,
			LinkPicker
		},
		data() {
			return {
				cat_id: '',
				showLink: false,
				dialogVisible: false,
				is_loading: false,
				is_submiting: false,
				query_type: 0,
				form: {
					id: '',
					name: '',
					cover_pic: '',
					adv_pic: '',
					is_show: 0,
					sort: '',
					parent_id: '',
					link: null,
				},
				parent_cat_name: null,
				cat_level_list: [],
			}
		},
		mounted() {
			this.form.id = this.$route.query.id;
			if (this.form.id) {
				this.getCat();
			}
			this.getCatLevel();
		},
		methods: {
			deleteLink() {
				this.form.link = null;
			},
			linkSelected(e) {

				this.form.link = e.url;
			},
			closeParent() {
				this.parent_cat_name = null;
				this.form.parent_id = '';
			},
			change(e) {
				if (e.length) {
					this.form.parent_id = e[e.length - 1];
				}

			},
			getCatLevel() {
				this.$request({
					url: '/mall/goods/cat-level',
					data: {
						cat_id: this.form.id
					}
				}).then(res => {

					if (res.code == 0) {
						this.cat_level_list = res.data.list;
					}
				})
			},
			getCat() {
				this.is_loading = true;
				this.$request({
					url: '/mall/goods/cat-edit', //this.$api.mall.goods.cat_edit
					data: {
						id: this.form.id
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.form = res.data.cat;
						this.parent_cat_name = res.data.parent_cat.name;

					}
				})
			},
			submit() {
				this.is_submiting = true;
				this.$request({
					url: '/mall/goods/cat-edit',
					data: this.form,
					method: 'post'
				}).then(res => {
					this.is_submiting = false;
					if (res.code == 0) {
						this.$message.success(res.msg);
						this.$go.back()
					} else {
						this.$message.error(res.msg);
					}
				})

			},
			deleteCoverPic() {
				this.form.cover_pic = '';
			},
			deleteAdvPic() {
				this.form.adv_pic = '';
			},
			chooseCoverPic(e) {

				if (e.length > 0) {
					this.form.cover_pic = e[0].url;
				}
			},
			chooseAdvPic(e) {

				if (e.length > 0) {
					this.form.adv_pic = e[0].url;
				}
			},
			cancel() {
				this.$go.back()
			},

		}
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
		}
	}

	.tag-box {
		margin: 0px;

		.tag-item {
			margin-right: 5px;
		}
	}
</style>
