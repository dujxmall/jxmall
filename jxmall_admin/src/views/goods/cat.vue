<template>

	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>分类列表</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="catEdit(0)">添加分类</el-button>
			</div>
			<div>
				<el-table :data="list" border style="width: 100%" v-loading="is_loading">
					<el-table-column prop="id" label="ID" width="50">
					</el-table-column>
					<el-table-column prop="sort" label="排序" width="50">
					</el-table-column>
					<el-table-column prop="name" label="名称">
					</el-table-column>
					<el-table-column prop="parent_name" label="上级分类">
					</el-table-column>
					<el-table-column label="小图">
						<template slot-scope="scope">
							<el-image :src='scope.row.cover_pic' style='width: 80px;height: 80px;'></el-image>
						</template>
					</el-table-column>
					<el-table-column label="广告图">
						<template slot-scope="scope">
							<el-image :src='scope.row.adv_pic' style='width: 80px;height: 80px;'></el-image>
						</template>
					</el-table-column>
					<el-table-column label="广告图链接">
						<template slot-scope="scope">
							<el-tag v-if="scope.row.link">
								{{scope.row.link}}
							</el-tag>
						</template>
					</el-table-column>

					<el-table-column label="状态">
						<template slot-scope='scope'>
							<el-tooltip class="btn" effect="dark" content="展示" placement="top"
								v-if="scope.row.is_show==1">
								<el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info"
									iconColor="red" title="是否隐藏？" @onConfirm="catShowChange(scope.row.id,0)">
									<el-button type="primary" size="mini" slot="reference">展示</el-button>
								</el-popconfirm>
							</el-tooltip>
							<el-tooltip class="btn" effect="dark" content="隐藏" placement="top"
								v-if="scope.row.is_show==0">
								<el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info"
									iconColor="red" title="是否展示？" @onConfirm="catShowChange(scope.row.id,1)">
									<el-button type="info" size="mini" slot="reference">隐藏</el-button>
								</el-popconfirm>
							</el-tooltip>
						</template>
					</el-table-column>

					<el-table-column label="操作" class="operation">
						<template slot-scope="{row,$index}">
							<el-tooltip class="btn" effect="dark" content="编辑" placement="top">
								<el-button type='primary' size='mini' icon="el-icon-edit-outline" circle
									@click='catEdit(row.id)'></el-button>
							</el-tooltip>

							<el-tooltip class="btn" effect="dark" content="删除" placement="top">
								<el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info"
									iconColor="red" :title="'确定删除'+row.name+'?'" @onConfirm="catDelete(row.id,$index)">
									<el-button type='danger' size='mini' icon="el-icon-delete-solid" circle
										slot="reference"></el-button>
								</el-popconfirm>
							</el-tooltip>
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
	</div>
</template>

<script>
	export default {
    name:'goods-cat',
		data() {
			return {
				keyword: '',
				activeTabName: 'status_0',
				search_date: [],
				is_loading: false,
				page: 1,
				list: [],
				status: -1,
				pagination: null
			}
		},
		mounted() {
			this.getList()
		},
		methods: {
			tabClick() {
				if (this.activeTabName == 'status_1') {
					this.status = 1;
				}
				if (this.activeTabName == 'status_2') {
					this.status = 0;
				}
				if (this.activeTabName == 'status_0') {
					this.status = -1;
				}
				this.getList();

			},
			search() {
				this.page = 1;
				this.getList();
			},
			getList() {
				this.is_loading = true;
				this.$request({
					url: '/mall/goods/cat', //this.$api.mall.goods.cat
					data: {
						page: this.page,
						status: this.status,

					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list
						this.pagination = res.data.pagination
					}
				})
			},
			pageChange(e) {
			 
				this.page = e;
				this.getList();
			},
			catEdit(id) {
				if (id) {
					this.$router.push({
						path: '/goods/cat-edit?id=' + id,

					})
				} else {
					this.$router.push({
						path: '/goods/cat-edit',

					})
				}

			},
			catShowChange(id, is_show) {
				this.$request({
					url: '/mall/goods/cat-show', //
					data: {
						id: id,
						is_show: is_show
					},
					method: 'post'
				}).then((res) => {
					if (res.code == 0) {
						this.getList();
						this.$message.success(res.msg)
					}
				})
			},
			catDelete(id, index) {
				this.$request({
					url: '/mall/goods/cat-delete', //
					data: {
						id: id
					},
					method: 'post'
				}).then((res) => {
					if (res.code == 0) {
						this.getList();
						this.$message.success(res.msg)
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	.btn {
		margin-right: 10px;

		i {
			font-size: 15px;
		}
	}
</style>
