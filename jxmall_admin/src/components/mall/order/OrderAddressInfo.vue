<template>

	<div>
		<el-dialog title="提示" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">

			<el-form v-if="order" ref="form" label-width="120px">
				<el-form-item label="收货人">
					<el-col :span="20">
						<el-input v-model="order.name"></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="手机号码">
					<el-col :span="20">
						<el-input v-model="order.mobile"></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="所属地区">
					<el-col :span="20">
						<div class="flex-row">
							<el-input v-model="area" @click='showAddressPickerDialog' disabled></el-input>
							<el-button style="margin-left: 10px;" @click="showAddressPicker">选择地区</el-button>
						</div>
					</el-col>

				</el-form-item>
				<el-form-item label="详细地址">
					<el-col :span="20">
						<el-input v-model="address_detail"></el-input>
					</el-col>
				</el-form-item>

			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="submit" :loading="is_submiting">确 定</el-button>
			</span>
		</el-dialog>

		<address-picker v-if="order" :openDialog='showAddressPickerDialog' @closed="closed" :level='level' @selected='addressSelected'></address-picker>
	</div>

</template>

<script>
	import AddressPicker from '@/components/common/AddressPicker'
	export default {
		name: 'OrderAddressInfo',
		components: {
			AddressPicker
		},
		data() {

			return {
				is_submiting: false,
				dialogVisible: false,
				showAddressPickerDialog: false,
				level: 3,
				newAddressInfo: null,
				area: '',
				address_detail: '',
			}
		},
		props: {
			showOrderAddressInfo: {
				type: Boolean,
				default: false
			},
			order: {
				type: Object,
				default: () => {
					return null
				}

			},

		},
		watch: {
			dialogVisible(newVal, oldVal) {

				if (!newVal) {

					this.$emit('addressModifyClose');
				}

			},
			showOrderAddressInfo(newVal, oldVal) {

				if (newVal) {
					this.dialogVisible = true
				} else {
					this.dialogVisible = false
				}
			},

			order(newVal, oldVal) {

				if (newVal) {
					this.newAddressInfo = {
						province_code: newVal.province_code,
						city_code: newVal.city_code,
						county_code: newVal.county_code,
						town_code: newVal.town_code,
					}

					if (newVal.town_code) {
						this.area = newVal.area.province_name + ' ' + newVal.area.city_name + ' ' + newVal.area.county_name + ' ' +
							newVal.area.town_name;
					} else {
						this.area = newVal.area.province_name + ' ' + newVal.area.city_name + ' ' + newVal.area.county_name;
					}
				}
			}
		},
		methods: {
			handleClose(e) {
				this.showAddressPickerDialog = false;
				this.dialogVisible = false;
				this.level = 3;
				this.$emit('addressModifyClose');
			},
			showAddressPicker() {
				if (this.order.town_code) {
					this.level = 4;
				}
				this.showAddressPickerDialog = true;

			},
			closed() {
				this.showAddressPickerDialog = false;
			},
			submit() {
				if (!this.address_detail) {
					this.$message.warning('请填写详细地址');
					return;
				}
				this.is_submiting = true;

				this.$request({
					url: '/mall/order/address-modify',
					data: {
						province_code: this.newAddressInfo.province_code,
						city_code: this.newAddressInfo.city_code,
						county_code: this.newAddressInfo.county_code,
						town_code: this.newAddressInfo.town_code,
						address: this.area + ' ' + this.address_detail,
						order_id: this.order.id,
						address_detail: this.address_detail,
					},
					method: 'post'
				}).then(res => {
					this.is_submiting = false;
					if (res.code == 0) {
						this.showAddressPickerDialog = false;
						this.dialogVisible = false;
						this.level = 3;

					}
				})
			},
			addressSelected(e) {
				this.showAddressPickerDialog = false;
				this.newAddressInfo = e
				if (this.order.town_code) {
					this.area = e.province_name + ' ' + e.city_name + ' ' + e.county_name + ' ' +
						e.town_name;
				} else {
					this.area = e.province_name + ' ' + e.city_name + ' ' + e.county_name;
				}

			}
		}
	}
</script>

<style>
</style>
