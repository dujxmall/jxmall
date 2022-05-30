<template id="test">

	<div class="container">
		<div class="diy-component-preview">
			<div class="content">
				<div style="height:80px;padding: 0 20px;overflow: hidden;" class="flex-x-between" :style="cContainerStyle">

				</div>
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px">
				<el-form-item label="占位高度">
					<el-slider show-input v-model="data.height"></el-slider>
				</el-form-item>
			</el-form>
		</div>
	</div>

</template>

<script>
	export default {
		name: 'Blank',
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
					height: 10,
				},
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
				this.data = this.value;
				this.data.height=parseInt(this.value.height)
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
