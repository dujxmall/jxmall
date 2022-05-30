<template>

	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>商品列表</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="goodsEdit(0)">添加商品</el-button>
			</div>
			<div>
				<el-tabs v-model="activeTabName" @tab-click="tabClick">
					<el-tab-pane label="全部" name="status_0"></el-tab-pane>
					<el-tab-pane label="已上架" name="status_1"></el-tab-pane>
					<el-tab-pane label="未上架" name="status_2"></el-tab-pane>

				</el-tabs>

				<el-form ref="searchForm" :inline="true" label-width="100px">
					<el-form-item label="创建日期">
						<el-date-picker value-format="yyyy-MM-dd" v-model="search_date" type="daterange"
							range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
						</el-date-picker>
					</el-form-item>
					<el-form-item label="商品名称">
						<el-input v-model='keyword' placeholder="请输入名称进行搜索" style="margin: 0 20px;width: 250px">
						</el-input>
					</el-form-item>
					<el-form-item>
						<el-button type='primary' icon='el-icon-search' @click='search'>搜索
						</el-button>
					</el-form-item>
				</el-form>
				<el-table :data="list" border style="width: 100%" v-loading="is_loading">
					<el-table-column prop="id" label="ID" width="100" fixed>
					</el-table-column>
					<el-table-column prop="sort" label="排序" width="80" fixed>
					</el-table-column>
					<el-table-column label="商品信息" fixed>
						<template slot-scope='scope'>
							<div class="flex-row flex-y-center">
								<div>
									<el-image :src="scope.row.cover_pic" style="width:80px;height: 80px;" fit="fill">
									</el-image>
								</div>
								<div
									style="padding:0 20px;height: 100px;overflow: hidden;text-overflow: ellipsis;width: 300px;">
									{{scope.row.name}}
								</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column width="100" prop="cat_name" label="分类" fixed>
					</el-table-column>
					<el-table-column width="100" prop="price" label="价格" fixed>
					</el-table-column>
					<el-table-column width="100" prop="total_stock" label="总库存" fixed>
					</el-table-column>
					<el-table-column width="100" prop="stock" label="当前库存" fixed>
					</el-table-column>
					<el-table-column width="100" prop="warning_stock" label="预警库存" fixed>
					</el-table-column>
					<el-table-column width="100" prop="sales" label="销量" fixed>
					</el-table-column>

					<el-table-column label="状态" width="200" fixed>
						<template slot-scope='scope'>
							<el-tooltip class="btn" effect="dark" content="删除" placement="top"
								v-if="scope.row.status==1">
								<el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info"
									iconColor="red" title="是否下架？" @confirm="goodsStatusChange(scope.row.id,0)">
									<el-button type="primary" size="mini" slot="reference">已上架</el-button>
								</el-popconfirm>
							</el-tooltip>
							<el-tooltip class="btn" effect="dark" content="删除" placement="top"
								v-if="scope.row.status==0">
								<el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info"
									iconColor="red" title="是否上架？" @confirm="goodsStatusChange(scope.row.id,1)">
									<el-button type="info" size="mini" slot="reference">未上架</el-button>
								</el-popconfirm>
							</el-tooltip>
						</template>
					</el-table-column>

					<el-table-column label="属性" width="200">
						<template slot-scope='scope'>
							<el-tooltip class="btn" effect="dark" content="设为推荐" placement="top"
								v-if="scope.row.is_recommend==0">
								<el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info"
									iconColor="red" title="是否设为推荐商品？" @confirm="goodsRecommendChange(scope.row.id,1)">
									<el-button type="text" style="color: #606266;" size="mini" slot="reference">推荐
									</el-button>
								</el-popconfirm>
							</el-tooltip>
							<el-tooltip class="btn" effect="dark" content="取消推荐" placement="top"
								v-if="scope.row.is_recommend==1">
								<el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info"
									iconColor="red" title="取消推荐？" @confirm="goodsRecommendChange(scope.row.id,0)">
									<el-button type="text" size="mini" slot="reference">推荐</el-button>
								</el-popconfirm>
							</el-tooltip>
						</template>
					</el-table-column>
					<el-table-column label="定时上下架" width="200">
						<template slot-scope="scope">
							<el-button type="text" @click="showUpDownModal(scope.row.id)">上下架</el-button>
						</template>
					</el-table-column>
					<el-table-column label="操作" class="operation" width="200">
						<template slot-scope="{row,$index}">
							<el-tooltip class="btn" effect="dark" content="编辑" placement="top">
								<el-button type='primary' size='mini' icon="el-icon-edit-outline" circle
									@click='goodsEdit(row.id)'></el-button>
							</el-tooltip>

							<el-tooltip class="btn" effect="dark" content="删除" placement="top">
								<el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info"
									iconColor="red" :title="'确定删除'+row.name+'?'" @confirm="goodsDelete(row.id,$index)">
									<el-button type='danger' size='mini' icon="el-icon-delete-solid" circle
										slot="reference"></el-button>
								</el-popconfirm>
							</el-tooltip>
						</template>
					</el-table-column>
				</el-table>
				<div style="text-align: right;margin: 20px 0;">
					<pagination :pagination="pagination" v-model="page" @pageChange="getList"></pagination>
				</div>
			</div>
		</el-card>
		<UpDownJob :show="showUpDown" :goods_id="goods_id" @close="closeUpDownJob"></UpDownJob>
	</div>
</template>

<script>
	import UpDownJob from '@/components/mall/goods/UpDownJob'

	export default {
		name: 'goods-index',
		components: {
			UpDownJob
		},
		data() {
			return {
				showUpDown: false,
				keyword: '',
				activeTabName: 'status_0',
				search_date: [],
				is_loading: false,
				page: 1,
				list: [],
				status: -1,
				goods_id: '',
				pagination: null
			}
		},
		mounted() {
			this.getList()
		},
		methods: {
			closeUpDownJob() {
				this.goods_id = ''
				this.showUpDown = false
			},
			showUpDownModal(id) {
				this.goods_id = id
				this.showUpDown = true
			},
			tabClick() {
				if (this.activeTabName == 'status_1') {
					this.status = 1

				}
				if (this.activeTabName == 'status_2') {
					this.status = 0
				}
				if (this.activeTabName == 'status_0') {
					this.status = -1
				}
				this.getList()

			},
			search() {
				this.page = 1
				this.getList()
			},
			getList() {
				this.is_loading = true
				this.$request({
					url: '/mall/goods/list',
					data: {
						page: this.page,
						status: this.status,
						keyword: this.keyword,
						search_date: this.search_date
					}
				}).then(res => {
					this.is_loading = false
					if (res.code == 0) {
						this.list = res.data.list
						this.pagination = res.data.pagination
					}
				})
			},
			pageChange(e) {
				
				this.page = e
				this.getList()
			},
			goodsEdit(id) {
				if (id) {

					this.$router.push({
						path: '/goods/edit',
						query: {
							id: id ? id : ''
						}
					})

				} else {

					this.$router.push({
						path: '/goods/edit'

					})
				}

			},
			goodsRecommendChange(id, is_recommend) {
				this.$request({
					url: '/mall/goods/recommend-change',
					data: {
						id: id,
						is_recommend: is_recommend
					},
					method: 'post'
				}).then((res) => {
					if (res.code == 0) {
						this.getList()
						this.$message.success(res.msg)
					}
				})
			},
			goodsStatusChange(id, status) {
				this.$request({
					url: '/mall/goods/status-change',
					data: {
						id: id,
						status: status
					},
					method: 'post'
				}).then((res) => {
					if (res.code == 0) {
						this.getList()
						this.$message.success(res.msg)
					}
				})

			},
			goodsDelete(id, index) {
				this.$request({
					url: '/mall/goods/delete',
					data: {
						id: id
					},
					method: 'post'
				}).then((res) => {
					if (res.code == 0) {
						this.getList()
						this.$message.success(res.msg)
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	.btn {
		margin-right: 20px;

		i {
			font-size: 25px;
		}
	}
</style>
