<template id="test">

	<div class="container">
		<div class="diy-component-preview">
			<div class="content">
				<el-carousel :height="data.height+'px'">
					<el-carousel-item v-for="(item,index) in data.list" :key="index" :style="{'borderRadius':data.circle+'px'}" style="overflow:hidden">
						<el-image :src="item.pic_url" style="height: 100%;width: 100%;" fit="fill"></el-image>
					</el-carousel-item>
				</el-carousel>
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px">
				<div style="width: 550px;">
					<el-form-item label="图片圆角">
						<el-slider show-input v-model="data.circle"></el-slider>
					</el-form-item>
					<el-form-item label="图片高度">
						<el-slider show-input v-model="data.height" :max="2048"></el-slider>
					</el-form-item>
				</div>
				<el-form-item label="轮播图片">
					<div v-for="(item,index) in data.list" :key="index" class="edit-nav-item">
						<div class="nav-edit-options">
							<el-button @click="itemDelete(index)" type="primary" icon="el-icon-delete" style="top: -6px;right: -31px;"></el-button>
						</div>
						<div flex="dir:left box:first cross:center">
							<div>
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center" v-if="item.pic_url">
									<img :src="item.pic_url" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del" @click="deleteIcon(item)"> <i class="el-icon-close"></i></span>
								</div>
								<file-picker v-if="!item.pic_url" style="margin-right: 5px;" v-model="item.pic_url" width="88" height="88">
									<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</div>
							<div style="margin-top: 10px;">
								<div>
									<el-input v-model="item.url" placeholder="点击选择链接" readonly size="small">
										<el-button slot="append" @click="selectLinkClick(index)">选择链接</el-button>
									</el-input>
								</div>
							</div>
						</div>
					</div>
					<el-button size="small" @click="addItem">添加图片</el-button>
				</el-form-item>

			</el-form>
			<link-picker :showLink="showLink" @selected="linkSelected" @close="showLink=!showLink">
			</link-picker>
		</div>


	</div>

</template>

<script>
	import LinkPicker from '@/components/mall/LinkPicker'
	export default {
		components: {
			LinkPicker
		},
		name: 'Banner',
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
					height: 375,
					list: [],
					circle: 5,
					circular: true,
					dots: true,
					autoPlay: true,
					interval: 2000,
					duration: 150,
				},
				dialogTableVisible: false,


			}
		},
		computed: {

			cNavStyle() {
				return `width:${100 / this.data.columns}%;`;
			},
		},
		created() {
			if (!this.value) {
				this.$emit('input', this.data)
			} else {
				this.data = this.value;
				this.data.circle = parseInt(this.data.circle)

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
		watch: {
			data: {
				deep: true,
				handler(newVal, oldVal) {
					this.$emit('input', newVal, oldVal);
				},
			}
		},
		methods: {

			addItem() {
				this.data.list.push({
					name: '',
					pic_url: '',
					url: '',
				});
			},
			deleteIcon(row) {
				row.pic_url = ''
			},
			itemDelete(index) {
				this.data.list.splice(index, 1);
			},
			linkSelected(list, params) {
				if (!list.length) {
					return;
				}
				const link = list[0];
				if (this.currentEditNavIndex !== null) {
					this.data.navs[this.currentEditNavIndex].openType = link.open_type;
					this.data.navs[this.currentEditNavIndex].url = link.new_link_url;
					this.data.navs[this.currentEditNavIndex].params = link.params;
					this.currentEditNavIndex = null;
				}
			},
			selectLinkClick(index) {
				this.currentEditNavIndex = index;
				this.showLink = true;
				 
			},


			linkSelected(e) {
				this.showLink = false;
				Object.assign(this.data.list[this.currentEditNavIndex], e)
				this.$forceUpdate();
			},
		}
	}
</script>

<style lang="scss" scoped="scoped">
	.content {
		width: 98%;

		margin: 0 auto;
		border-radius: 5px;
		overflow: hidden;
	}

	.container {
		.edit-item {
			border: 1px solid #e2e2e2;
			line-height: normal;

			margin-bottom: 5px;
			max-width: 450px;
		}


		.edit-nav-item {
			border: 1px solid #e2e2e2;
			line-height: normal;
			padding: 5px;
			margin-bottom: 5px;
			max-width: 450px;


			.nav-edit-options {
				position: relative;



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
	}
</style>
