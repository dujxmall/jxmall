<template id="test">
	<div>
	<div class="mobile-edit">
			<div class="nav-container" :style="cContainerStyle">
				<div :style="cStyle" flex="dir:left">
					<div v-for="(navGroup,groupIndex) in cNavGroups" flex="dir:left" style="width: 750px;flex-wrap:wrap;">
						<div v-for="(nav,navIndex) in navGroup" :style="cNavStyle" class="nav-item">
							<img :src="nav.icon">
							<div :style="'color:'+data.color+';'">{{nav.name}}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="diy-edit" v-if="active">
			<el-form label-width="100px" @submit.native.prevent>

				<el-form-item label="输入">
					<el-input size="small" v-model="data.name"></el-input>
				</el-form-item>
			</el-form>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'Test',
		props: {
			value: Object,
			active: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				data: {
					name: '',
				}
			}
		},

		created() {
			if (!this.value) {
				this.$emit('input', JSON.parse(JSON.stringify(this.data)))
			} else {
				this.data = JSON.parse(JSON.stringify(this.value));
			}
		},
		computed: {},
		watch: {
			data: {
				deep: true,
				handler(newVal, oldVal) {
					this.$emit('input', newVal, oldVal)
				},
			},
			active(newVal, oldVal) {

			}
		},
	}
</script>

<style>
	.diy-edit {
		position: absolute;
		right: 0;
		width: 500px;
		padding: 20px;
		top: 0;
	
	
	}
	
	.mobile-edit {}
</style>
