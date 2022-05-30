<template>
	<div class="cat-picker">
		<el-dialog :title="title" width="1100px" :visible.sync="dialogVisible">
			<el-row v-loading="dialogLoading" :gutter="20" style="margin-top: -30px;">
				<template v-if="first_list.length > 0">
					<el-col :span="8">
						<h3>一级分类</h3>
						<div class="goods-cat-list active">
							<div :class="{'active': current1 == option.id ? true : false}" class="cat-item flex-row" flex="dir:left box:first"
							 v-for="(option,index) in first_list" :key="option.id">
								<el-checkbox @change="optionClick(option)" v-model="option.checked" :label="option.id" size="mini">
									<span style="display: none;">{{option.name}}</span>
								</el-checkbox>
								<div @click="selectCats(option,0)" flex="box:last cross:center">
									<span style="width: 100px;">{{option.name}}</span>
									<i v-if="option.is_child" class="el-icon-arrow-right"></i>
								</div>
							</div>
						</div>
					</el-col>
					<el-col :span="8" v-if="second_list.length > 0">
						<h3>二级分类</h3>
						<div class="goods-cat-list">
							<div :class="{'active': current2 == option.id ? true : false}" class="cat-item flex-row" flex="dir:left box:first"
							 v-for="(option,index) in second_list" :key="option.id">

								<el-checkbox @change="optionClick(option)" v-model="option.checked" :label="option.id" size="mini">
									<span style="display: none;">{{option.name}}</span>
								</el-checkbox>

								<div @click="selectCats(option,1)" flex="box:last cross:center">
									<span>{{option.name}}</span>
									<i v-if="option.is_child" class="el-icon-arrow-right"></i>
								</div>
							</div>
						</div>
					</el-col>
					<el-col :span="8" v-if="third_list.length > 0">
						<h3>三级分类</h3>
						<div class="goods-cat-list">
							<div :class="{'active': current3 == option.id ? true : false}" class="cat-item flex-row" flex="dir:left box:first"
							 v-for="(option,index) in third_list" :key="option.id">
								<el-checkbox @change="optionClick(option)" v-model="option.checked" :label="option.id" size="mini">
									<span style="display: none;">{{option.name}}</span>
								</el-checkbox>
								<div @click="selectCats(option,2)" flex="box:last cross:center">
									<span>{{option.name}}</span>
									<i v-if="option.is_child" class="el-icon-arrow-right"></i>
								</div>
							</div>
						</div>
					</el-col>
				</template>
				<template v-else>
					<div flex="main:center" style="align-items: center;margin-top: 20px;">
						<span>无系统分类</span>
						<el-button style="display: inline-block;margin-left: 10px" flex="main:center" type="primary" size="small" @click="$navigate({r: 'mall/cat/edit'})">
							请先添加商品分类
						</el-button>
					</div>
				</template>
			</el-row>
			<div class="tag-box" v-if="cats.length > 0">
				<el-tag type="warning" class="tag-item" @close="deleteCat(item, index)" closable v-for="(item, index) in cats" :key="item.value">
					{{item.name}}
				</el-tag>
			</div>
			<span slot="footer" class="dialog-footer">
				<el-button size='small' @click="dialogVisible = false">取 消</el-button>
				<el-button size='small' type="primary" @click="confirm">确 定</el-button>
			</span>
		</el-dialog>
	</div>

</template>

<script>
	export default {
		props: {
			mch_id: {
				type: Number,
				default: 0
			},
			newCats: {
				type: Array,
				default: function() {
					return []
				}
			},
			showCatPicker: {
				type: Boolean,
				default: false
			}

		},
		data() {
			return {
				dialogVisible: false,
				first_list: [], // 商品分类列表
				second_list: [],
				third_list: [],
				cats: [], //用于前端已选的分类展示
				dialogLoading: false,
				title: '选择分类',
				cat_type: 0, //0一级分类  1 二级分类 2三级分类
				current1: '',
				current2: '',
				current3: '',
				parent_cat_id: 0,
			}
		},
		watch: {
			showCatPicker(newVal, oldVal) {
				 
				if (newVal) {
					this.openDialog()
				}

			},
			dialogVisible(newVal, oldVal) {

				 
				if (!newVal) {
					this.$emit('closed')
				}

			}

		},
		methods: {
			openDialog() {
				let self = this;
				this.getCats();
				self.title = self.mch_id > 0 ? '选择多商户分类' : '选择分类';
				this.cats = [];
				this.children = [];
				this.third = [];
				this.dialogVisible = true;
			},
			// 获取商品分类
			getCats() {
				let self = this;
				self.dialogLoading = true;
				this.$request({
					url: '/mall/goods/cat-list',
					data: {
						mch_id: self.mch_id,
						type: this.cat_type,
						parent_cat_id: this.parent_cat_id
					},
				}).then(e => {
					self.dialogLoading = false;
					if (e.code === 0) {
						if (this.cat_type == 0) {
							self.first_list = e.data.list;
						}
						if (this.cat_type == 1) {
							self.second_list = e.data.list;
						}
						if (this.cat_type == 2) {
							self.third_list = e.data.list;
						}
						//	self.setCats();
					} else {
						self.$message.error(e.msg);
					}
				}).catch(e => {
					self.dialogLoading = false;
				});
			},
			deleteCat(option, index) {
				let self = this;
				self.cats.splice(index, 1);
				if (option.type == 0) {
					self.first_list.forEach(function(item) {
						if (item.id === option.id) {
							item.checked = false;
						}
					})
				}
				if (option.type == 1) {
					self.second_list.forEach(function(item) {
						if (item.id === option.id) {
							item.checked = false;
						}
					})
				}
				if (option.type == 2) {
					self.third_list.forEach(function(item) {
						if (item.id === option.id) {
							item.checked = false;
						}
					})
				}

			},
			optionClick(option) {
				let self = this;
				let sign = true;
				 
				if (option.type == 0) {
					if (option.checked) {
						this.cats.push(option)
					} else {
						if (this.cats.length == 1) {
							this.cats.splice(0, 1)
						}
					}
				}
				if (option.type == 1) {
					if (option.checked) {
						this.cats.push(option)
					} else {
						if (this.cats.length == 2) {
							this.cats.splice(1, 1)
						}

					}


				}
				if (option.type == 2) {
					if (option.checked) {
						this.cats.push(option)
					} else {
						if (this.cats.length == 3) {
							this.cats.splice(2, 1)
						}
					}
				}

			},
			// 选择分类
			selectCats(option, type) {
				let self = this;

				if (option.type == 0) {

					this.current1 = option.id;
				}
				if (option.type == 1) {

					this.current2 = option.id;
				}
				if (option.type == 2) {

					this.current2 = option.id;
				}
				if (type === 0) {
					this.cat_type = 1;
					this.parent_cat_id = option.id
				}
				if (type === 1) {
					this.cat_type = 2;
					this.parent_cat_id = option.id
				}
				this.getCats();
			},
			confirm() {
				this.dialogVisible = false;
				this.$emit('selected', this.cats)
			}
		}
	}
</script>

<style>
	.cat-picker .goods-cat-list {
		border: 1px solid #E8EAEE;
		border-radius: 5px;
		margin-top: -5px;
		padding: 10px 0;
		overflow: scroll;
		height: 400px;
	}

	.cat-picker .goods-cat-list .cat-item {
		cursor: pointer;
		padding: 5px 10px;
	}

	.cat-picker .goods-cat-list .active {
		background: #FAFAFA;
	}

	.cat-picker .el-checkbox {
		margin-right: 0;
	}

	.cat-picker .tag-box {
		margin: 20px 0;
	}

	.cat-picker .tag-box .tag-item {
		margin-right: 5px;
	}
</style>
