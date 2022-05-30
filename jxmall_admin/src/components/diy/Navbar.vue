<template id="test">

	<div class="diy-nav">
		<div class="diy-component-preview">
			<div class="content">
				<div style="width: 750px;flex-wrap:wrap;display: flex;min-height: 100px;box-sizing: border-box;"
					:style="cContainerStyle" flex="dir:left">
					<div v-for="(item,navIndex) in data.list" :style="cNavStyle" class="nav-item">
						<el-image :src="item.pic_url" style="width: 88px;height: 88px;">
							<div slot="error" class="image-slot" style="font-size: 77px;color: #b2b2b2">
								<i class="el-icon-picture-outline"></i>
							</div>
						</el-image>
						<div style="padding-top: 5px;" :style="'color:'+data.color+';'">{{item.name}}</div>
					</div>
				</div>
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px">
				<el-form-item label="背景颜色">
					<el-color-picker v-model="data.background"></el-color-picker>
				</el-form-item>
				<el-form-item label="文字颜色">
					<el-color-picker v-model="data.color"></el-color-picker>
				</el-form-item>
				<el-form-item label="每行个数">
					<el-radio v-model="data.columns" :label="3">3</el-radio>
					<el-radio v-model="data.columns" :label="4">4</el-radio>
					<el-radio v-model="data.columns" :label="5">5</el-radio>
				</el-form-item>
				<el-form-item label="导航图标">
					<div v-for="(item,index) in data.list" class="edit-nav-item">
						<div class="nav-edit-options">
							<el-button @click="itemDelete(index)" type="primary" icon="el-icon-delete"
								style="top: -6px;right: -31px;"></el-button>
						</div>
						<div flex="dir:left box:first cross:center">
							<div>
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
									v-if="item.pic_url">
									<img :src="item.pic_url" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del" @click="deleteIcon(item)"> <i
											class="el-icon-close"></i></span>
								</div>
								<file-picker v-if="!item.pic_url" style="margin-right: 5px;" v-model="item.pic_url"
									width="88" height="88">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</div>
							<div style="margin-top: 10px;">
								<el-input v-model="item.name" placeholder="名称" size="small" style="margin-bottom: 5px">
								</el-input>
								<div>
									<el-input v-model="item.url" placeholder="点击选择链接" readonly size="small">
										<el-button size="small" @click="selectLinkClick(index)" slot="append">选择链接
										</el-button>

									</el-input>
								</div>
							</div>
						</div>
					</div>
					<el-button size="small" @click="addItem">添加图标</el-button>
				</el-form-item>
			</el-form>
		</div>
		<link-picker :showLink="showLink" @selected="linkSelected" @close="showLink=false" @cancel="showLink=false">
		</link-picker>
	</div>

</template>

<script>
	import LinkPicker from '@/components/mall/LinkPicker'
	export default {
		components: {
			LinkPicker
		},
		name: 'Test',
		props: {
			value: {
				type: Object,
				default: () => {
					return null;
				}
			},
			active: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				showLink: false,
				currentEditNavIndex: null,
				data: {
					background: '#ffffff',
					color: '#353535',
					columns: 3,
					list: [],
				},
				dialogTableVisible: false,
				page: 1,
				pageCount: 0,
				navList: [],
				listLoading: false,
				multipleSelection: [],
			}
		},
		computed: {
			cContainerStyle() {
				return `background:${this.data.background};`;
			},
			cNavStyle() {
				return `width:${100 / this.data.columns}%;`;
			},
		},
		created() {
			if (!this.value) {
				this.$emit('input', this.data)
			} else {
				this.data = this.value;
				this.data.columns = parseInt(this.value.columns);
			}
		},
		watch: {
			data: {
				deep: true,
				handler(newVal, oldVal) {
					this.$emit('input', newVal, oldVal);
				},
			}
		},
		methods: {
			linkSelected(e) {
				if (!this.data.list[this.currentEditNavIndex].name || this.data.list[this.currentEditNavIndex].name ==
					'') {
					this.data.list[this.currentEditNavIndex].name = e.url_name
				}
				this.data.list[this.currentEditNavIndex].url = e.url
				this.$forceUpdate();
				this.showLink = false;
			},
			addItem() {
				this.data.list.push({
					pic_url: '',
					name: '',
					url: '',
					open_type: '',
				});
			},
			deleteIcon(row) {
				row.pic_url = ''
			},
			itemDelete(index) {
				this.data.list.splice(index, 1);
			},

			selectLinkClick(index) {
				this.showLink = true;
				this.currentEditNavIndex = index;
			},

			updateNav() {
				let self = this;
				self.multipleSelection.forEach(function(item, index) {
					self.data.list.push(item)
				});
				self.dialogTableVisible = false;
			}
		}
	}
</script>

<style lang="scss">
	.diy-nav {
		.content {
			width: 98%;
			margin: 0 auto;
			border-radius: 5px;
			overflow: hidden;


		}

		.nav-item {
			text-align: center;
			font-size: 24px;
			padding: 20px 0;
		}

		.nav-item>div {
			height: 25px;
			line-height: 25px;
		}

		.nav-item img {
			display: block;
			width: 88px;
			height: 88px;
			margin: 0 auto 5px auto;
		}

		.edit-nav-item {
			border: 1px solid #e2e2e2;
			line-height: normal;
			padding: 5px;
			margin-bottom: 5px;
			max-width: 450px;
		}

		.nav-icon-upload {
			display: block;
			width: 65px;
			height: 65px;
			line-height: 65px;
			border: 1px dashed #8bc4ff;
			color: #8bc4ff;
			background: #f9f9f9;
			cursor: pointer;
			background-size: 100% 100%;
			font-size: 28px;
			text-align: center;
			vertical-align: middle;
		}

		.nav-edit-options {
			position: relative;
		}

		.nav-edit-options {
			.el-button {
				height: 25px;
				line-height: 25px;
				width: 25px;
				padding: 0;
				text-align: center;
				border: none;
				border-radius: 0;
				position: absolute;
				margin-left: 0;
			}
		}
	}
</style>
