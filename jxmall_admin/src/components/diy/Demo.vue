<template id="test">

	<div class="container">
		<div class="diy-component-preview">
			<div class="content">
				
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px">
				
				<el-form-item label="背景颜色">
					<el-color-picker v-model="data.background"></el-color-picker>
				</el-form-item>
				<el-form-item label="标题文字颜色">
					<el-color-picker v-model="data.title_color"></el-color-picker>
				</el-form-item>
				<el-form-item label="副标题文字颜色">
					<el-color-picker v-model="data.sub_title_color"></el-color-picker>
				</el-form-item>
				<el-form-item label="跳转链接">
					<el-input v-model="data.url" placeholder="点击选择链接" readonly size="small">
						<link-picker @selected="linkSelected" slot="append">
							<el-button size="small">选择链接</el-button>
						</link-picker>
					</el-input>
				</el-form-item>
			</el-form>
		</div>

		<el-dialog title="热图列表" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
			<span>这是一段信息</span>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="dialogVisible = false">确 定</el-button>
			</span>
		</el-dialog>


	</div>

</template>

<script>
	import LinkPicker from '@/components/mall/LinkPicker'
	export default {
		components: {
			LinkPicker
		},
		name: 'TitleBar',
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
				dialogVisible: false,
				currentEditNavIndex: null,
				data: {
				
					background: '#ffffff',
					color: '#353535',
					title_color: '#000000',
					sub_title_color: '#000000'
				},
				dialogTableVisible: false,
				page: 1,

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
			handleClose(e) {
				this.dialogVisible = false;
			},
			showHotPicker() {
				this.dialogVisible = true;
			},
			linkSelected(e) {
				Object.assign(this.data, e)
				this.$forceUpdate();
			 
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
	}
</style>
