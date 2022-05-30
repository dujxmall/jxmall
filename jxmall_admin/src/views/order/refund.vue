<template>

	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>售后订单</span>

			</div>
			<div>
				<el-tabs v-model="activeTabName" @tab-click="tabClick">
					<el-tab-pane label="全部" name="all"></el-tab-pane>
					<el-tab-pane label="待处理" name="status_0"></el-tab-pane>
					<el-tab-pane label="已同意" name="status_1"></el-tab-pane>
					<el-tab-pane label="已拒绝" name="status_2"></el-tab-pane>

				</el-tabs>
				<div style="margin-bottom: 20px;">
					<el-row>
						<el-col :span="6">
							<el-date-picker value-format="yyyy-MM-dd" v-model="search_date" type="daterange"
								range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
							</el-date-picker>

						</el-col>
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
					<el-table-column prop="id" label="ID" width="100" fixed>
					</el-table-column>
					<el-table-column label="订单信息" width="350" fixed>
						<template slot-scope='scope'>
							<div>
								订单号：{{scope.row.o_order_no}}
							</div>
							<div>
								{{scope.row.name}} {{scope.row.mobile}}
							</div>
							<div>
								{{scope.row.address}}
							</div>
						</template>
					</el-table-column>

					<el-table-column label="商品信息" width="500" fixed>
						<template slot-scope='scope'>
							<div class="flex-row">
								<div>
									<el-image :src="scope.row.cover_pic" style="width:80px;height: 80px;" fit="fill">
									</el-image>
								</div>
								<div style="padding:0 20px;">
									<div>
										{{scope.row.goods_name}}
									</div>
									<div>x {{scope.row.num}}</div>
									<div v-if="scope.row.attr.length">
										<div v-for="item in scope.row.attr">
											{{item.attr_group_name}} : {{item.attr_name}}
										</div>
									</div>
								</div>
							</div>
						</template>
					</el-table-column>



					<el-table-column label="用户信息" width="260" fixed>
						<template slot-scope='scope'>
							<div class="flex-row">
								<el-image :src="scope.row.avatar_url" style="width:80px;height: 80px;" :fit="'fill'">
								</el-image>
								<div style="padding: 20px;">
									{{scope.row.nickname}}
								</div>
							</div>
						</template>
					</el-table-column>

					<el-table-column label="售后类型">
						<template slot-scope="scope">
							<div v-if="scope.row.type==1">换货</div>
							<div v-if="scope.row.type==0">退款退货</div>
						</template>
					</el-table-column>
					<el-table-column label="退款金额">
						<template slot-scope="scope">
							<div v-if="scope.row.type==1">-</div>
							<div v-if="scope.row.type==0">{{scope.row.refund_price}}</div>
						</template>
					</el-table-column>
					<el-table-column label="原因" prop="content">

					</el-table-column>
					<el-table-column label="配图">
						<template slot-scope="scope">
							<div v-if="scope.row.pic_list.length>0">
								<el-image style="height: 100px;width: auto;" fit="fit"
									:preview-src-list="scope.row.pic_list" :src="scope.row.pic_list[0]"></el-image>
							</div>
							<div v-else>
								<el-empty :image-size="50"></el-empty>
							</div>
						</template>
					</el-table-column>

					<el-table-column label="状态">
						<template slot-scope='scope'>
							<el-tooltip class="btn" effect="dark" content="处理售后" placement="top"
								v-if="scope.row.status==0">
								<el-popconfirm confirmButtonText='同意' cancelButtonText='拒绝' icon="el-icon-info"
									iconColor="red" title="是否同意售后？" @confirm="agree(scope.row.id,1)"
									@cancel="agree(scope.row.id,2)">
									<el-button type="danger" size="mini" slot="reference">处理</el-button>
								</el-popconfirm>
							</el-tooltip>
							<div v-if="scope.row.status==1">
								<el-button type="text">已同意</el-button>
							</div>
							<div v-if="scope.row.status==2">
								<el-button type="text" style="color: #F22C40;">已拒绝</el-button>
							</div>
						</template>
					</el-table-column>
					<el-table-column label="操作" class="operation">
						<template slot-scope="{row,$index}">
							<div class="flex-row">
								<div v-if="row.type==0">
									<el-tooltip v-if="row.is_refund==0&&row.status==1" class="btn" effect="dark"
										content="删除" placement="top">
										<el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info"
											iconColor="red" :title="'确定确定打款给'+row.nickname+'?'"
											@confirm="refundPay(row.id,$index)">
											<el-button type='text' size='mini' circle slot="reference">打款</el-button>
										</el-popconfirm>
									</el-tooltip>
									<el-button type='text' size='mini' v-if="row.is_refund==1&&row.status==1">已退款
									</el-button>
								</div>
							</div>
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
		name: 'order-refund',
		data() {
			return {
				keyword: '',
				activeTabName: 'all',
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
			refundPay(id, index) {


				this.$request({
					url: '/mall/order/refund-pay',
					data: {
						id: id
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg)
						this.getList();
					}

				})


			},
			tabClick() {
				if (this.activeTabName == 'status_1') {
					this.status = 1;
				}
				if (this.activeTabName == 'status_2') {
					this.status = 2;
				}
				if (this.activeTabName == 'status_0') {
					this.status = 0;
				}
				if (this.activeTabName == 'all') {
					this.status = -1;
				}
				this.getList();
			},
			search() {
				this.list = [];
				this.page = 1;
				this.getList();
			},
			getList() {
				this.is_loading = true;
				let start_at = '';
				let end_at = '';
				if (this.search_date) {
					start_at = this.search_date[0];
					end_at = this.search_date[1];
				}
				this.$request({
					url: '/mall/order/refund',
					data: {
						page: this.page,
						status: this.status,
						keyword: this.keyword,
						start_at: start_at,
						end_at: end_at
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

			agree(id, status) {
			 
				this.$request({
					url: '/mall/order/refund-status',
					data: {
						id: id,
						status: status
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
