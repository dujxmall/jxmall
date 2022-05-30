<template id="test">

	<div class="container">
		<div class="diy-component-preview">
			<div class="content">
				<div :style="cContainerStyle" v-if="data.type==1">
					<el-image fit="fill" style="height: 100%;width: 100%;" :src="data.list[0].pic_url" alt="">
						<div slot="error" class="image-slot">
							<i class="el-icon-picture-outline"></i>
						</div>
					</el-image>
				</div>
				<div style="display: flex;" :style="cContainerStyle" v-if="data.type==2">
					<el-image fit="fill" style="height: 100%;width: 50%;" :src="data.list[0].pic_url" alt="">
						<div slot="error" class="image-slot">
							<i class="el-icon-picture-outline"></i>
						</div>
					</el-image>
					<el-image fit="fill" style="height: 100%;width: 50%;" :src="data.list[0].pic_url" alt="">
						<div slot="error" class="image-slot">
							<i class="el-icon-picture-outline"></i>
						</div>
					</el-image>
				</div>
				<div style="display: flex;" :style="cContainerStyle" v-if="data.type==3">
					<div style="width: 50%;">
						<el-image fit="fill" style="height: 100%;width: 100%;" :src="data.list[0].pic_url" alt="">
							<div slot="error" class="image-slot">
								<i class="el-icon-picture-outline"></i>
							</div>
						</el-image>
					</div>
					<div style="width: 50%;height: 100%;">
						<el-image fit="fill" style="height: 50%;width: 100%;" :src="data.list[1].pic_url" alt="">
							<div slot="error" class="image-slot">
								<i class="el-icon-picture-outline"></i>
							</div>
						</el-image>
						<el-image fit="fill" style="height:  50%;width: 100%;" :src="data.list[2].pic_url" alt="">
							<div slot="error" class="image-slot">
								<i class="el-icon-picture-outline"></i>
							</div>
						</el-image>
					</div>
				</div>
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px">
				<el-form-item label="占位高度">
					<el-slider show-input :min="0" :max="2048" v-model="data.height"></el-slider>
				</el-form-item>
				<el-form-item label="样式选择">

					<el-radio-group v-model="data.type">
						<el-radio :label="1">样式1</el-radio>
						<el-radio :label="2">样式2</el-radio>
						<el-radio :label="3">样式3</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="图片1" v-if="data.type>0">
					<div class="edit-nav-item">
						<div flex="dir:left box:first cross:center">
							<div>
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
									v-if="data.list[0].pic_url">
									<img :src="data.list[0].pic_url" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del" @click="data.list[0].pic_url=''">
										<i class="el-icon-close"></i></span>
								</div>
								<file-picker v-if="!data.list[0].pic_url" style="margin-right: 5px;"
									v-model="data.list[0].pic_url" width="88" height="88">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</div>
							<div style="margin-top: 10px;">
								<div>
									<el-input v-model="data.list[0].url" placeholder="点击选择链接" readonly size="small">
										<el-button @click="selectLinkClick(data.list[0])" slot="append" size="small">
											选择链接</el-button>
									</el-input>
								</div>
							</div>
						</div>
					</div>
				</el-form-item>
				<el-form-item label="图片2" v-if="data.type>1">
					<div class="edit-nav-item">
						<div flex="dir:left box:first cross:center">
							<div>
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
									v-if="data.list[1].pic_url">
									<img :src="data.list[1].pic_url" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del" @click="data.list[1].pic_url=''">
										<i class="el-icon-close"></i></span>
								</div>
								<file-picker v-if="!data.list[1].pic_url" style="margin-right: 5px;"
									v-model="data.list[1].pic_url" width="88" height="88">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</div>
							<div style="margin-top: 10px;">
								<div>
									<el-input v-model="data.list[1].url" placeholder="点击选择链接" readonly size="small">
										<el-button @click="selectLinkClick(data.list[1])" slot="append" size="small">
											选择链接</el-button>
									</el-input>
								</div>
							</div>
						</div>
					</div>
				</el-form-item>

				<el-form-item label="图片3" v-if="data.type>2">
					<div class="edit-nav-item">
						<div flex="dir:left box:first cross:center">
							<div>
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
									v-if="data.list[2].pic_url">
									<img :src="data.list[2].pic_url" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del" @click="data.list[2].pic_url=''">
										<i class="el-icon-close"></i></span>
								</div>
								<file-picker v-if="!data.list[2].pic_url" style="margin-right: 5px;"
									v-model="data.list[2].pic_url" width="88" height="88">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</div>
							<div style="margin-top: 10px;">
								<div>
									<el-input v-model="data.list[2].url" placeholder="点击选择链接" readonly size="small">
										<el-button @click="selectLinkClick(data.list[2])" slot="append" size="small">
											选择链接</el-button>
									</el-input>
								</div>
							</div>
						</div>
					</div>
				</el-form-item>
			</el-form>
		</div>
		<link-picker :showLink="showLink" @selected="linkSelected" @cancel="showLink=false" @close="showLink=false">
		</link-picker>
	</div>
</template>

<script>
	import LinkPicker from '@/components/mall/LinkPicker'
	export default {
		components: {
			LinkPicker
		},
		name: 'ImgGroup',
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
				data: {
					height: 375,
					type: 1,
					list: [{
						pic_url: '',
						url: '',
					}, {
						pic_url: '',
						url: '',
					}, {
						pic_url: '',
						url: '',
					}, ],

				},
				currentEditRow: '',
				dialogTableVisible: false,
			}
		},
		computed: {
			cContainerStyle() {
				let style = `height:${this.data.height}px;`;

				return style;
			},
			cNavStyle() {
				return `width:${100 / this.data.columns}%;`;
			},

		},
		created() {
			if (!this.value) {
				this.$emit('input', this.data)
			} else {
				this.data = JSON.parse(JSON.stringify(this.value));
				this.data.type = parseInt(this.data.type);
				this.data.height = parseInt(this.data.height);
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
				Object.assign(this.currentEditRow, e);
				this.showLink = false;
				this.$forceUpdate();
			},
			selectLinkClick(row) {
				this.currentEditRow = row;
				this.showLink = true;
			},
			deleteIcon(row) {
				row.pic_url = ''
			},
		}
	}
</script>

<style lang="scss">
	.container {
		.edit-item {
			border: 1px solid #e2e2e2;
			line-height: normal;
			padding: 5px;
			margin-bottom: 5px;
			max-width: 450px;
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

	}
</style>
