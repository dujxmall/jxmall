<template>

	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>分销商品</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="goodsSync">同步商品</el-button>
			</div>
			<div>
				<div style="margin-bottom: 20px;">
					<el-row>
						<el-col :span="6">
							<div class="flex-row">
								<el-input v-model='keyword' placeholder="请输入名称进行搜索"></el-input>
								<el-button type='primary' size='mini' style="margin-left: 10px;" icon='el-icon-search'
									@click='search'>搜索</el-button>
							</div>
						</el-col>
					</el-row>
				</div>

				<el-table :data="list" border style="width: 100%" v-loading="is_loading">
					<el-table-column prop="id" label="商品ID" width="100">
					</el-table-column>

					<el-table-column label="商品信息" width="500">
						<template slot-scope='scope'>
							<div class="flex-row">
								<div>
									<el-image :src="scope.row.cover_pic" style="width:80px;height: 80px;" :fit="'fill'">
									</el-image>
								</div>
								<div style="padding:0 20px;">
									{{scope.row.name}}
								</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="price" label="价格">
					</el-table-column>
					<el-table-column prop="stock" label="库存">
					</el-table-column>
					<el-table-column prop="sales" label="销量">
					</el-table-column>
					<el-table-column label="操作" class="operation">
						<template slot-scope="{row,$index}">
							<el-tooltip class="btn" effect="dark" content="佣金设置" placement="top">
								<el-button type='text' size='mini' @click='goodsEdit(row.share_goods_id)'>佣金设置
								</el-button>
							</el-tooltip>
							<el-tooltip class="btn" effect="dark" content="参与分销" placement="top"
								v-if="row.is_share_goods==0">
								<el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info"
									iconColor="red" :title="'确分该商品参与分销?'"
									@onConfirm="goodsShareStatus(row.share_goods_id,1)">
									<el-button type='text' size='mini' slot="reference">参与分销</el-button>
								</el-popconfirm>
							</el-tooltip>
							<el-tooltip class="btn" effect="dark" content="取消分销" placement="top"
								v-if="row.is_share_goods==1">
								<el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info"
									iconColor="red" :title="'确定取消分销商品'+row.name+'?'"
									@onConfirm="goodsShareStatus(row.share_goods_id,0)">
									<el-button type='text' size='mini' slot="reference">不参与分销</el-button>
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
		name: 'plugins-share-goods-list',
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

			search() {
				this.page = 1;
				this.getList();
			},
			getList() {
				this.is_loading = true;
				this.$request({
					url: "/plugin/share/mall/goods/list",
					data: {
						page: this.page,
						status: this.status,
						keyword: this.keyword

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
			goodsSync() {
				this.is_loading = true
				this.$request({
					url: "/plugin/share/mall/goods/sync",
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.$message.success(res.msg);
						this.getList();
					}
				})
			},
			goodsEdit(id) {
				this.$router.push({
					path: '/plugins/share/price-edit',
					query: {
						id: id ? id : ''
					}
				})
			},

			goodsShareStatus(id, status) {
				this.$request({
					url: "/plugin/share/mall/goods/share-status",
					data: {
						id: id,
						is_share_goods: status
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
		margin-right: 20px;

		i {
			font-size: 25px;
		}
	}
</style>
