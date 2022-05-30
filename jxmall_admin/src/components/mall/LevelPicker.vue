<template>
	<div>
		<el-dialog title="选择会员等级" :visible.sync="dialogVisible" width="60%" :before-close="handleClose">

			<div>

				<scroll-bar :height="400">
					<el-table :data="list" style="width: 100%" v-loading="is_loading">
						<el-table-column prop="level" label="会员等级">
						</el-table-column>
						<el-table-column prop="created_at" label="注册时间">
						</el-table-column>
						<el-table-column label="成交">
							<template slot-scope="scope">
								<div>订单：<span style="color: #FF9952;">{{scope.row.order_num}}</span></div>
								<div>金额：<span style="color: #FF9952;">{{scope.row.total_order_price}}</span></div>
							</template>
						</el-table-column>
						<el-table-column label="佣金">
							<template slot-scope="scope">
								<div>佣金：<span style="color: #FF9952;">{{scope.row.price}}</span></div>
								<div>累计：<span style="color: #FF9952;">{{scope.row.total_price}}</span></div>
							</template>
						</el-table-column>

						<el-table-column label="操作">
							<template slot-scope="scope">
								<el-button type="text" v-if="scope.$index!=select_user_index"
									@click="selectUser(scope.$index,scope.row.id)">选择</el-button>
								<el-button type="primary" size="mini" v-if="scope.$index==select_user_index">已选
								</el-button>
							</template>
						</el-table-column>
					</el-table>

				</scroll-bar>

			</div>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="confirmSelect">确 定</el-button>
			</span>
		</el-dialog>


	</div>
</template>

<script>
	export default {
		props: {
			show: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				is_loading: false,
				page: 1,
				list: [],
				dialogVisible: false,
				select_user_index: -1,
				select_user_id: 0,
				keyword: '',

			};
		},
		watch: {

			show(newVal, oldVal) {
				if (newVal) {
					this.dialogVisible = true;
				} else {
					this.dialogVisible = false;
				}
			},
			dialogVisible(newVal, oldVal) {
				if (!this.dialogVisible) {
					this.$emit('close')
				}
			}
		},
		created() {
			this.page = 1;
			this.list = [];
			this.select_user_id = 0;
			this.select_user_index = -1;
			this.getList();
		},
		methods: {

			selectUser(index, user_id) {
				this.select_user_index = index;
				this.select_user_id = user_id;
			},
			confirmSelect() {
				if (this.select_user_id == 0) {
					this.$message.warning('请选择用户');
					return;
				}
				this.$emit('selected', {
					user_id: this.select_user_id
				});
			},

			handleClose(e) {
				this.dialogVisible = false;
			},

			getList() {
				this.is_loading = true;
				this.$request({
					url: "/mall/user/level",

				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list;
					}
				})

			},
		}

	}
</script>


<style>
</style>
