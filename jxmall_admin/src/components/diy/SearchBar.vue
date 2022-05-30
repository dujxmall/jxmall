<template id="test">

	<div class="container">
		<div class="diy-component-preview">
			<div class="content">
				<div style="height:60px;padding: 0 20px;overflow: hidden;" class="flex-x-between" :style="cContainerStyle">
					<div class="flex-row flex-y-center" style="color:#C2C2C2 ;">
						<div><i class="el-icon-search"></i></div>
						<div style="padding:0 20px;">{{data.tips}}</div>
					</div>
				</div>
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px">
				<el-form-item label="背景颜色">
					<el-color-picker v-model="data.background"></el-color-picker>
				</el-form-item>
				<el-form-item label="是否圆角">
					<el-radio-group v-model="data.is_circle">
						<el-radio :label="0">正常</el-radio>
						<el-radio :label="1">圆角</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="搜索提示语">
					<el-input v-model="data.tips" placeholder="输入搜索提示语" size="small">
					</el-input>
				</el-form-item>
			</el-form>
		</div>
	</div>

</template>

<script>
	export default {
		name: 'SearchBar',
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
				currentEditNavIndex: null,
				data: {
					background: '#ffffff',
					is_circle: 0,
					tips: '输入内容进行搜索',
				},
				dialogTableVisible: false,
			}
		},
		computed: {
			cContainerStyle() {
				let style = `background:${this.data.background};`;
				if (this.data.is_circle == 1) {
					style = style + `border-radius:40px;`;
				}
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
				this.data = this.value;
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
