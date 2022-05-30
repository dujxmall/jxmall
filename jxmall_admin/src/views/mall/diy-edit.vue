<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>页面编辑</span>
				<div style="float: right;">
					<el-button style="padding: 3px 0;" type="text" @click="saveOther">另存为</el-button>
					<el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
				</div>
			</div>
			<div flex="box:first" style="margin-bottom: 10px;min-width: 1280px;height: 725px;display: flex;">
				<div class="all-components">
					<el-form @submit.native.prevent label-width="80px">
						<el-form-item label="页面名称">
							<el-input size="small" v-model="name"></el-input>
						</el-form-item>
					</el-form>
					<div class="component-group">
						<div class="component-group-name">组件预设</div>
						<div class="component-list flex-row flex-y-center" flex="">
							<template v-for="item in option_list" class="flex-y-center">
								<div class=" component-item" @click="optionClick(item)">
									<img class="component-icon" :src="item.icon">
									<div class="component-name">{{item.display_name}}</div>
								</div>
							</template>
						</div>
					</div>
				</div>
				<div style="margin-left: 0px;position: relative;width: 100%;padding-left: 40px;">
					<div style="overflow-y: auto;padding: 0 25px;width: 435px;height: 705px;">
						<div class="mobile-framework" style="height: 750px;">
							<img style="height: 80px;width: 100%;border: solid #F3F3F3 1px;border-top-left-radius: 4px;border-top-right-radius: 4px;"
								src="@/assets/statics/mall/diy/phone-head.jpg" alt="">

							<div class="mobile-framework-body" v-loading='is_loading'>
								<draggable v-model="components" :options="{filter:'.active',preventOnFilter:false}"
									v-if="components && components.length">
									<div v-for="(component, index) in components" :key="component.key"
										@click="showComponentEdit(component,index)"
										:class="(component.active?'active':'')">
										<div class="diy-component-options" v-if="component.active">
											<el-button type="primary" icon="el-icon-delete"
												@click.stop="deleteComponent(index)" style="left: -25px;top:0;">
											</el-button>
											<el-button type="primary" icon="el-icon-document-copy"
												@click.stop="copyComponent(index)" style="left: -25px;top:30px;">
											</el-button>
											<el-button v-if="index > 0 && components.length > 1" type="primary"
												icon="el-icon-arrow-up" @click.stop="moveUpComponent(index)"
												style="right: -25px;top:0;"></el-button>
											<el-button v-if="components.length > 1 && index < components.length-1"
												type="primary" icon="el-icon-arrow-down"
												@click.stop="moveDownComponent(index)" style="right: -25px;top:30px;">
											</el-button>
										</div>
										<component :is="component.name" :active="component.active" :key="index"
											v-model="component.data"></component>
									</div>
								</draggable>
								<div v-else flex="main:center cross:center"
									style="height: 200px;color: #adb1b8;text-align: center;">
									<div>
										<el-empty description="请从左侧组件库添加组件"></el-empty>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</el-card>
		<el-dialog title="提示" :visible.sync="showSaveOther" width="10%">
			<el-form>
				<el-form-item label="另存名称">
					<el-input style="width: 300px;" type="text" v-model="other_name" placeholder="输入名称">
					</el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="showSaveOther = false">取 消</el-button>
				<el-button type="primary" @click="saveOtherConfirm">保存</el-button>
			</span>
		</el-dialog>
		<div v-if="fixed_components.length" v-for="(item,index) in fixed_components">
			<component :is="item.name" :show="item.active" v-model="item.data" @confirm="confirm"></component>
			<span v-if="item.active"></span>
		</div>
	</div>
</template>

<script>
	import Blank from '@/components/diy/Blank'
	import SearchBar from '@/components/diy/SearchBar'
	import Banner from '@/components/diy/Banner'
	import TitleBar from '@/components/diy/TitleBar'
	import Navbar from '@/components/diy/Navbar'
	import draggable from 'vuedraggable'
	import GoodsGroup from '@/components/diy/GoodsGroup'
	import ImgGroup from '@/components/diy/ImgGroup'
	import Notice from '@/components/diy/Notice'
	import AlertAdv from '@/components/diy/AlertAdv'
	import VideoBlock from '@/components/diy/VideoBlock'
	import CustomGoods from '@/components/diy/CustomGoods'
	import PtBlock from '@/components/diy/PtBlock'
	import HotImage from '@/components/diy/HotImage'
	import FloatButton from '@/components/diy/FloatButton'
	export default {
		name: 'DiyEdit',
		components: {
			SearchBar,
			Blank,
			Banner,
			TitleBar,
			Navbar,
			draggable,
			GoodsGroup,
			ImgGroup,
			Notice,
			AlertAdv,
			VideoBlock,
			CustomGoods,
			PtBlock,
			HotImage,
			FloatButton
		},
		data() {
			return {
				is_loading: false,
				showSaveOther: false,
				showFixedComponent: false,
				other_name: '',
				active: true,
				id: '',
				option_list: [],
				fixed_list: [],
				components: [],
				fixed_components: [],
				name: '',
				test: {
					name: 'test'
				},
				i: 0,
				select_index: -1

			}
		},
		created() {

			this.getComponents()
			if (this.$route.query.id) {
				this.id = this.$route.query.id
				this.getPage()
			}
		},
		methods: {
			confirm() {
				for (let i in this.fixed_components) {
					this.fixed_components[i].active = false
				}
			
			},
			show() {

			},

			saveOther() {
				this.showSaveOther = true
			},
			getComponents() {
				this.$request({
					url: '/mall/mall/components'
				}).then((res) => {
					if (res.code == 0) {
						this.option_list = res.data.list
						this.fixed_list = res.data.fixed_list
					}
				})

			},
			saveOtherConfirm() {
				this.is_loading = true
				if (!this.name || this.name == '') {
					this.$message.warning('请输入页面名称')
					return
				}
				this.$request({
					url: '/mall/mall/diy-edit',
					data: {
						data: JSON.stringify(this.components),
						name: this.other_name
					},
					method: 'post'
				}).then((res) => {
					this.is_loading = false
					this.showSaveOther = false
					if (res.code == 0) {

						this.$message.success(res.msg)
					}
				})
			},
			getPage() {
				this.is_loading = true
				this.$request({
					url: '/mall/mall/diy-edit',
					data: {
						id: this.id
					}
				}).then(res => {
					this.is_loading = false
					if (res.code == 0) {
						this.name = res.data.page.name
						let components = res.data.page.data
						for (let i in components) {
							components[i].active = false
							this.components.push(components[i])
						}

					}
				})
			},
			save() {

				if (!this.name || this.name == '') {
					this.$message.warning('请输入页面名称')
					return
				}
				this.is_loading = true
				this.$request({
					url: '/mall/mall/diy-edit',
					data: {
						data: JSON.stringify(this.components),
						id: this.id,
						name: this.name
					},
					method: 'post'
				}).then((res) => {
					this.is_loading = false
					if (res.code == 0) {
						this.$go.back()
						this.$message.success(res.msg)
					}

				})
			},
			moveUpComponent(index) {
				this.swapComponents(index, index - 1)
			},
			moveDownComponent(index) {
				this.swapComponents(index, index + 1)
			},
			swapComponents(index1, index2) {
				this.components[index2] = this.components.splice(index1, 1, this.components[index2])[0]
			},
			deleteComponent(index) {
				this.components.splice(index, 1)
			},
			showComponentEdit(component, index) {
				this.select_index = index
				for (let i in this.components) {
					if (i == index) {
						this.components[i].active = true
					} else {
						this.components[i].active = false
					}
				}
			},
			componentClick(index) {
				for (let i in this.components) {
					this.components[i].active = false
				}
				this.components[index].active = true
			},

			fixedClick(index) {
				for (let i in this.fixed_components) {
					this.fixed_components[i].active = false
				}
				this.fixed_components[index].active = true
			},

			optionClick(row) {
				row = JSON.parse(JSON.stringify(row))
				if (row.is_fixed == 1) {
					this.showFixedComponent = true

					let flag = false

					let fixed_components = JSON.parse(JSON.stringify(this.fixed_components))
					for (let i in fixed_components) {
						if (fixed_components[i].name == row.name) {

							flag = true
							break
						}
					}
					if (!flag) {
						fixed_components.push(row)
					}

					for (let i in fixed_components) {
						if (fixed_components[i].name == row.name) {
							fixed_components[i].active = true
						} else {
							fixed_components[i].active = false
						}
					}
					this.fixed_components = fixed_components

					this.$forceUpdate()
					return
				}

				for (let i in this.components) {
					this.components[i].active = false
				}
				row.active = true
				row.data = null

			 
				if (this.select_index != -1) {
					this.components.splice(this.select_index + 1, 0, row)
					return
				}
				this.components.push(row)
			}
		}
	}
</script>

<style lang="scss">
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
		display: none
	}

	.el-scrollbar {
		height: 100%;
	}

	.all-components {
		background: #fff;
		padding: 20px;
		width: 390px;
		text-align: center;
	}

	.all-components .component-group {
		border: 1px solid #eeeeee;
		width: 370px;
		margin-bottom: 20px;
	}

	.all-components .component-group:last-child {
		margin-bottom: 0;
	}

	.all-components .component-group-name {
		height: 35px;
		line-height: 35px;
		background: #f7f7f7;
		padding: 0 20px;
		border-bottom: 1px solid #eeeeee;
	}

	.all-components .component-list {
		margin-right: -2px;
		margin-top: -2px;
		flex-wrap: wrap;

	}

	.all-components .component-list .component-item {
		text-align: center;
		padding: 15px 0 0;
		cursor: pointer;
		margin: 5px;
		width: 82px;
		height: 82px;
		box-sizing: border-box;
		padding: 10px;
		background: #f4f6f8;
		border-radius: 2px;
		border: none;
		font-size: 14px;
	}

	.all-components .component-list .component-icon {
		width: 40px;
		height: 40px;
		/*border: 1px solid #eee;*/
	}

	.all-components .component-list .component-name {}

	.content {
		width: 98%;
		height: 100%;
		margin: 0 auto;
		border-radius: 5px;
		overflow: hidden;
		color: #212121;
	}

	.mobile-framework {
		width: 375px;
		height: 100%;

		.diy-component-preview {
			cursor: pointer;
			position: relative;
			zoom: 0.5;
			-moz-transform: scale(0.5);
			-moz-transform-origin: top left;
			font-size: 28px;

		}

		@-moz-document url-prefix() {
			.diy-component-preview {
				cursor: pointer;
				position: relative;
				-moz-transform: scale(0.5);
				-moz-transform-origin: top left;
				font-size: 28px;
				width: 200% !important;
				height: 100%;
				margin-bottom: auto;

				.content {
					width: 98%;
					height: 100%;
					margin: 0 auto;
					border-radius: 5px;
					overflow: hidden;
				}
			}

			.active .diy-component-preview {
				border: 2px dashed #409EFF;
				left: -2px;
				right: -2px;
				width: calc(200% + 4px) !important;

				.content {
					width: 98%;
					height: 100%;
					margin: 0 auto;
					border-radius: 5px;
					overflow: hidden;
				}

			}
		}

		.diy-component-preview:hover {
			box-shadow: inset 0 0 10000px rgba(0, 0, 0, .03);
		}

		.diy-component-edit {
			position: absolute;
			top: 0;
			bottom: 0;
			left: 465px;
			right: 0;
			background: #fff;
			padding: 20px;
			display: none;
			overflow: auto;
		}
	}

	.mobile-framework-header {
		height: 60px;
		line-height: 60px;

		color: #fff;
		text-align: center;
		background: url('~@/assets/statics/mall/diy/phone-head.jpg') no-repeat;
	}

	.mobile-framework-body {
		min-height: 645px;
		border: 1px solid #e2e2e2;
		background: #f5f7f9;
	}

	.diy-component-options {
		position: relative;
	}

	.diy-component-options .el-button {
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

	.mobile-framework .active .diy-component-preview {
		border: 2px dashed #409EFF;
		left: -2px;
		right: -2px;
		width: calc(100% + 4px);
	}

	.mobile-framework .active .diy-component-edit {
		display: block;

		width: 800px;
	}

	.all-components {
		max-height: 725px;
	}

	.bottom-menu {
		text-align: center;
		height: 54px;
		width: 100%;
	}

	.bottom-menu .el-card__body {
		padding-top: 10px;
	}

	.el-dialog {
		min-width: 800px;
	}
</style>
