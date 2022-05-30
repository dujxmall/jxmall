<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>商城列表</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="addMall">新增商城</el-button>
			</div>
			<div>
				<el-table v-loading="loading" :data="list" border style="width: 100%">
					<el-table-column prop="id" label="ID" width="100">
					</el-table-column>
					<el-table-column label="logo">
						<template slot-scope='scope'>
							<el-avatar :size="50" :src="scope.row.logo"></el-avatar>
						</template>
					</el-table-column>
					<el-table-column prop="name" label="商城名称">
					</el-table-column>
					<el-table-column prop="created_at" label="创建时间">
					</el-table-column>
					<el-table-column label="是否过期">
						<template slot-scope="scope">
							<div v-if="scope.row.is_expire==0">
								未过期
							</div>
							<div v-if="scope.row.is_expire==1">
								已过期
							</div>
						</template>
					</el-table-column>
					<el-table-column label="有效期">
						<template slot-scope="scope">
							<div v-if="scope.row.is_has_expire==0">
								永久有效
							</div>
							<div v-if="scope.row.is_has_expire==1">
								<span>
									{{scope.row.end_at}}
								</span>
							</div>
						</template>
					</el-table-column>
					<el-table-column label="操作">
						<template slot-scope="scope">
							<el-button type="text" @click='deleteMall(scope.row)'
								v-if="admin_info&&admin_info.admin_type==0">删除</el-button>
							<el-button type="text" size="small" @click="editMall(scope.row)">编辑</el-button>
							<el-button type="text" size="small" @click="enterMall(scope.row)">进入商城</el-button>
						</template>
					</el-table-column>
				</el-table>
				<div style="text-align: right;margin: 20px 0;">
					<el-pagination v-if="list.length > 0" background :page-size="pagination.pageSize"
						@current-change="pageChange" layout="prev, pager, next" :current-page="pagination.current_page"
						:total="pagination.total_count">
					</el-pagination>
				</div>
			</div>
		</el-card>

		<el-dialog title="编辑" :visible.sync="dialogVisible" width="950px" :before-close="handleClose">
			<div style="width: 70%;margin: 0 auto;">
				<el-form ref="form" :model="form" label-width="120px">
					<el-form-item label="商城名称">
						<el-input v-model="form.name"></el-input>
					</el-form-item>
					<el-form-item label="开启有效期">
						<el-switch v-model="form.is_has_expire" active-value="1" inactive-value="0"></el-switch>
					</el-form-item>
					<el-form-item label="有效期" v-if="form.is_has_expire==1">
						<el-col :span="12">
							<el-date-picker v-model="form.end_at" value-format="yyyy-MM-dd HH:mm:ss" type="datetime"
								placeholder="截止于">
							</el-date-picker>
						</el-col>
					</el-form-item>
					<el-form-item label="授权插件">
						<el-tree node-key="name" ref="tree" :default-checked-keys="form.plugins" :data='plugin_map'
							show-checkbox>
						</el-tree>
					</el-form-item>
				</el-form>
			</div>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = !dialogVisible">取 消</el-button>
				<el-button type="primary" @click="submit">确 定</el-button>
			</span>
		</el-dialog>
	</div>
</template>

<script>
	import {
		setMallId,
	} from '@/utils/auth'
	export default {
		name: 'index',
		components: {

		},
		data() {
			return {
				dialogVisible: false,
				mall: null,
				showEdit: false,
				list: [],
				loading: false,
				pagination: null,
				page: 1,
				admin_info: null,
				plugin_map: [],
				temp_form: {
					is_has_expire: 0,
					name: '',
					end_at: '',
					id: '',
					plugins: [],
				},
				form: {
					is_has_expire: 0,
					name: '',
					end_at: '',
					id: '',
					plugins: [],
				}
			}
		},
		mounted() {
			this.getList();
			this.getPluginMap();
		},
		methods: {
			handleClose(e) {

			},
			getPluginMap() {
				this.$request({
					url: "/admin/admin/plugin-map"
				}).then(e => {
					if (e.code == 0) {
						this.plugin_map = e.data.list;
					}
				})
			},
			submit() {
				this.form.plugins = this.$refs.tree.getCheckedKeys();
				this.$request({
					url: '/admin/mall/edit',
					method: 'post',
					data: this.form
				}).then(res => {
					console.log(res)
					if (res.code == 0) {
						this.dialogVisible = false;
						this.$message({
							message: res.msg,
							type: 'success'
						})
					}
				})
			},
			editMall(row) {
				if (this.dialogVisible) {
					return;
				}
				this.dialogVisible = true;
				this.$request({
					url: "/admin/mall/info",
					data: {
						mall_id: row.id
					}
				}).then(e => {
					this.$refs.tree.setCheckedKeys([]);
					this.form = e.data.mall;
				})
			},
			enterMall(row) {
				this.$request({
					url: "/admin/mall/enter",
					data: {
						mall_id: row.id
					}
				}).then(e => {
					if (e.code == 0) {
						this.$confirm('确定离开当前商城', '提示').then(e => {
							setMallId(row.id);
							location.href = '/admin';
						})

					}
				})
			},
			deleteMall(row) {
				this.$confirm('确认删除商城' + row.name, '提示', {
						type: 'warning'
					})
					.then((e) => {
						this.$request({
							url: '/admin/mall/delete',
							data: {
								id: row.id
							},
							method: 'post'
						}).then(res => {
							if (res.code == 0) {
								this.$message.success(res.msg);
								this.getList();
							}
						})
					})
					.catch(() => {})
			},
			refresh() {
				this.getList();
			},
			pageChange(e) {
				this.page = e
				this.getList()
			},
			getList() {
				this.loading = true
				this.$request({
					url: '/admin/mall/list',
					data: {
						page: this.page
					}
				}).then(res => {
					this.loading = false
					if (res.code == 0) {
						this.list = res.data.list
						this.pagination = res.data.pagination
					}
				})
			},
			addMall() {
				this.showEdit = true
			},
			cancel() {
				this.mall = null;
				this.showEdit = !this.showEdit;
			},
			btnClick() {
				this.$router.replace({
					path: '/admin/mall',
				})
			}
		}
	}
</script>

<style>
</style>
