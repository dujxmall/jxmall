<template>
	<div class="cat-picker">
		<el-dialog title="定时上下架" width="1100px" :visible.sync="show" @close="close">
			<div class="flex-y-center">
				<div class="flex-y-center">
					<div style="padding: 0 20px;">
						执行操作
					</div>
					<el-radio v-model="form.status" label="1">下架</el-radio>
					<el-radio v-model="form.status" label="2">上架</el-radio>
				</div>
				<div style="padding: 0 20px;">
					<el-date-picker v-model="form.action_time" value-format="yyyy-MM-dd HH:mm:ss" type="datetime"
						placeholder="选择日期时间">
					</el-date-picker>
				</div>
				<div>
					<el-button @click="save">确定执行</el-button>
				</div>
			</div>
			<el-table :data="list" style="width: 100%" v-loading="is_loading">
				<el-table-column label="执行任务">
					<template slot-scope="scope">
						<div v-if="scope.row.status==1">
							下架
						</div>
						<div v-if="scope.row.status==2">
							上架
						</div>
					</template>
				</el-table-column>
				<el-table-column prop="action_time" label="执行时间">
				</el-table-column>
				<el-table-column prop="created_time" label="创建时间">
				</el-table-column>
			</el-table>
			<span slot="footer" class="dialog-footer">
				<el-button size='small' @click="close">关闭</el-button>

			</span>
		</el-dialog>
	</div>

</template>

<script>
	export default {
		props: {
			goods_id: {
				type: String,
				default: ''
			},
			show: {
				type: Boolean,
				default: false
			},
		},
		data() {
			return {
				is_loading: false,
				list: [],
				form: {
					status: '',
					action_time: '',
					goods_id: '',
				}
			}
		},
		watch: {
			show(newVal, oldVal) {
				if (newVal) {
					this.getList();
				}
			}
		},
		 
		methods: {
			getList() {
				this.is_loading = true;
				this.$request({
					url: "/mall/goods/goods-up-down-job",
					data: {
						goods_id: this.goods_id
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list;
					}
				})

			},
			save() {
				this.is_loading = true;
				this.form.goods_id = this.goods_id
				this.$request({
					url: "/mall/goods/goods-up-down-job",
					data: this.form,
					method: 'post'
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.$message.success(res.msg);
						this.getList();
					}
				})
			},
			close() {
				this.list = [];
				this.$emit('close');
			},
		}
	}
</script>

<style>
</style>
