<template>
	<div>
		<el-dialog title="提示" :visible.sync="dialogVisible" width="50%" :before-close="handleClose">
			<el-form v-if="order" ref="form" label-width="120px">
				<el-form-item label="发货方式">
					<el-col :span="20">
						<el-radio v-model="send_type" :label="0">整单发货</el-radio>
						<el-radio v-model="send_type" :label="1">分包发货</el-radio>
						<el-radio v-model="send_type" :label="2">无需物流</el-radio>
					</el-col>
				</el-form-item>

				<div v-if="send_type!=2">
					<el-form-item label="收货人">
						<el-col :span="20">
							{{order.name}}
						</el-col>
					</el-form-item>
					<el-form-item label="联系方式">
						<el-col :span="20">
							{{order.mobile}}
						</el-col>
					</el-form-item>
					<el-form-item label="收货地址">
						<el-col :span="20">
							{{order.address}}
						</el-col>
					</el-form-item>
					<el-form-item label="快递公司">
						<el-col :span="20">
							<el-select v-model="express_name" placeholder="请选择" @change="expressSelect"
								style="width: 100%;">
								<el-option v-for="(item,index) in express_list" :label="item.name" :value="index">
								</el-option>
							</el-select>
						</el-col>
					</el-form-item>
					<el-form-item label="快递单号">
						<el-col :span="20">
							<el-input v-model="express_no"></el-input>
						</el-col>
					</el-form-item>


				</div>

				<el-table :data="order_detail_list" style="width: 100%" v-if='send_type==1'
					@selection-change="handleSelectionChange">
					<el-table-column type="selection" width="55" :selectable="checkSelectable">
					</el-table-column>
					<el-table-column label="商品信息">
						<template slot-scope='scope'>
							<div class="flex-row">
								<el-image :src='scope.row.goods.cover_pic' class="goods-pic"></el-image>
								<div style="padding-top: 30px;">
									<div>{{scope.row.goods.name}}</div>
								</div>
							</div>
						</template>
					</el-table-column>

					<el-table-column label="规格" width="180">
						<template slot-scope='scope'>
							<div v-if="scope.row.attr">
								<div v-for="(item,index) in scope.row.attr">
									{{item.attr_group_name}}：<el-tag size='mini'>{{item.attr_name}}</el-tag>
								</div>
							</div>
							<div v-if="!scope.row.attr">
								规格：<el-tag size='mini'>默认</el-tag>
							</div>
						</template>
					</el-table-column>

					<el-table-column prop="num" label="数量">
					</el-table-column>

				</el-table>


			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="submit" :loading="is_submiting">确 定</el-button>
			</span>
		</el-dialog>


	</div>

</template>

<script>
	export default {
		name: 'OrderAddressInfo',
		components: {

		},
		data() {
			return {
				is_submiting: false,
				dialogVisible: false,
				area: '',
				express_list: [],
				express_no: '',
				send_type: 0,
				send_list: [],
				express_name: '',
				selectExpress: {
					ali_key: '',
					code: "",
					id: "",
					key: "",
					name: ""
				}
			}
		},
		props: {
			showOrderSend: {
				type: Boolean,
				default: false
			},
			order: {
				type: Object,
				default: () => {
					return null
				}
			},
			order_detail_list: {
				type: Array,
				default: () => {
					return []
				}
			}
		},
		watch: {
			dialogVisible(newVal, oldVal) {
				if (!newVal) {
					this.$emit('orderSendClose');
				}
			},
			showOrderSend(newVal, oldVal) {
				if (newVal) {
					this.dialogVisible = true
				} else {
					this.dialogVisible = false
				}
			},

			order(newVal, oldVal) {
				if (newVal) {

				}
			}
		},
		mounted() {
			this.getExpress();
		},
		methods: {
			checkSelectable(row) {
				if (row.is_send == 1) {
					return false;
				}
				return true;
			},
			handleSelectionChange(e) {
				this.send_list = [];
				for (let i in e) {
					this.send_list.push(e[i].id);
				}
			},
			expressSelect(e) {
				this.selectExpress = this.express_list[e];
			},
			getExpress() {
				this.$request({
					url: '/common/express/express'
				}).then(res => {
					if (res.code == 0) {
						this.express_list = res.data.list
					}
				})
			},
			handleClose(e) {
				this.showOrderSend = false;
				this.dialogVisible = false;
				this.level = 3;
				this.$emit('addressModifyClose');
			},

			closed() {},
			submit() {
				if (this.send_type == 1) {
					if (this.send_list.length == 0) {
						this.$message.warning('请选择需要发货的商品');
						return;
					}
				}
				if (this.send_type != 2) {
					if (this.express_no == '') {
						this.$message.warning('请输入快递单号');
						return;
					}
					if (this.selectExpress.code == '') {
						this.$message.warning('请选择快递公司');
						return;
					}
				}
				this.is_submiting = true;
				this.$request({
					url: '/mall/order/send',
					data: {
						order_id: this.order.id,
						send_type: this.send_type,
						express_name: this.selectExpress.name,
						express_no: this.express_no,
						express_code: this.selectExpress.key,
						send_list: this.send_list
					},
					method: 'post'
				}).then(res => {
					this.is_submiting = false;
					if (res.code == 0) {
						this.showOrderSend = false;
						this.dialogVisible = false;
						this.$emit('refresh')

					}
				})
			},

		}
	}
</script>

<style>
</style>
