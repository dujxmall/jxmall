<template>
	<div>

		<slot></slot>
		<el-dialog title="选择地址" :visible.sync="dialogVisible" width="40%">
			<el-cascader ref="address" v-model="value" :options="list" :props="optionProps" @change="handleChange" clearable
			 style="width: 100%;"></el-cascader>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="confirm">确 定</el-button>
			</span>

		</el-dialog>

	</div>
</template>

<script>
	import address from '@/statics/address.json'
	export default {
		name: 'AddressPicker',
		props: {
			openDialog: {
				type: Boolean,
				default: true
			},
			level: {
				type: Number,
				default: 3
			}
		},
		data() {
			return { //初始化数据
				optionProps: {
					children: 'list',
					label: 'name',
					multiple: false,
					value: 'code'
				},
				value: '',
				dialogVisible: false,
				address: null,
				list: [],
			}
		},
		created() {
			let address_data = localStorage.getItem('ADDRESS_DATA');
			if (address_data) {
				this.list = JSON.parse(address_data);
				return;
			}
			this.$request({
				url: '/common/address/address-data'
			}).then(res => {
				 
				if (res.code == 0) {
					this.list = res.data.list
					localStorage.setItem('ADDRESS_DATA', JSON.stringify(res.data.list));
				}
			})
		},
		watch: {
			openDialog(newVal, oldVal) {
				this.dialogVisible = newVal
			 
			},
			dialogVisible(newVal, oldVal) {
				if (!newVal) {
					this.$emit('closed')
				}
			}

		},
		mounted() {

		},
		methods: {
			handleChange(e) {
				let data = this.$refs.address.getCheckedNodes();
				data = data[0];
				let address = {
					'province_code': e[0],
					'city_code': e[1],
					'county_code': e[2],
					'province_name': data.pathLabels[0],
					'city_name': data.pathLabels[1],
					'county_name': data.pathLabels[2],
				}
				if (e.length == 4) {
					address.town_code = e[3];
					address.town_name = data.pathLabels[3];
				}
				this.address = address;
			},

			confirm() {
				this.dialogVisible = false;
				this.$emit('selected', this.address);
			},
		}
	}
</script>

<style scoped>

</style>
