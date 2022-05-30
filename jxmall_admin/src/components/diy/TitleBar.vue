<template>
	<div class="container">
		<div class="diy-component-preview">
			<div class="content">
				<div style="height:80px;padding: 0 20px;" class="flex-x-between" :style="cContainerStyle">
					<div :style="{color:data.title_color}">{{data.title}}</div>
					<div :style="{color:data.sub_title_color}">
						<span>{{data.sub_title}}</span>
						<span> <i class="el-icon-arrow-right"></i> </span>
					</div>
				</div>
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
				<el-form-item label="标题">
					<el-input v-model="data.title" placeholder="输入标题" size="small">
					</el-input>
				</el-form-item>
				<el-form-item label="副标题">
					<el-input v-model="data.sub_title" placeholder="输入标题" size="small">
					</el-input>
				</el-form-item>
				<el-form-item label="跳转链接">
					<el-input v-model="data.url" placeholder="点击选择链接" readonly size="small">
						<el-button size="small" slot="append" @click="showLink=false">选择链接</el-button>
					</el-input>
				</el-form-item>
			</el-form>
			<link-picker :showLink="showLink" @selected="linkSelected" @close="showLink=false" @cancel="showLink=false">
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
				showLink: false,
				currentEditNavIndex: null,
				data: {
					background: '#ffffff',
					color: '#353535',
					title_color: '#000000',
					sub_title_color: '#000000',
					title: '标题',
					sub_title: '查看更多',
					url: '',
				},
				dialogTableVisible: false,
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
			linkSelected(e) {
				this.showLink = false;

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
