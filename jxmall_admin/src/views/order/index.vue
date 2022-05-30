<template>

	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>订单列表</span>
			</div>

			<div style="margin-bottom: 20px;">
				<el-form ref="searchForm" :inline="true" label-width="100px">
					<div class="flex-y-center">
						<el-form-item label="关键词">
							<el-input v-model='keyword' placeholder="关键词" style="width: 350px;">
								<template slot="prepend">
									<el-dropdown @command="keywordChange">
										<el-button>
											{{ keyword_display_name }}<i class="el-icon-arrow-down el-icon--right"></i>
										</el-button>
										<el-dropdown-menu slot="dropdown">
											<el-dropdown-item command="order_no">订单编号</el-dropdown-item>
											<el-dropdown-item command="nickname">会员昵称</el-dropdown-item>
											<el-dropdown-item command="user_id">会员ID</el-dropdown-item>
											<el-dropdown-item command="name">收件人信息</el-dropdown-item>
											<el-dropdown-item command="address">地址信息</el-dropdown-item>
											<el-dropdown-item command="goods_name">商品名称</el-dropdown-item>
										</el-dropdown-menu>
									</el-dropdown>
								</template>
							</el-input>
							<el-input v-model="parent_id" placeholder="团队长ID" style="width: 300px;margin-left: 20px;">
							</el-input>
						</el-form-item>
						<el-form-item label="支付方式">
							<el-dropdown @command="paymentChange">
								<el-button>
									{{ payment_name }}<i class="el-icon-arrow-down el-icon--right"></i>
								</el-button>
								<el-dropdown-menu slot="dropdown">
									<el-dropdown-item command="not_pay">未支付</el-dropdown-item>
									<el-dropdown-item command="sys_pay">后台支付</el-dropdown-item>
									<el-dropdown-item command="balance_pay">余额支付</el-dropdown-item>
									<el-dropdown-item command="wechat_pay">微信支付</el-dropdown-item>
								</el-dropdown-menu>
							</el-dropdown>
						</el-form-item>
						<el-form-item label="下单时间">
							<el-date-picker value-format="yyyy-MM-dd" v-model="search_date" type="daterange"
								range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
							</el-date-picker>
						</el-form-item>
						<el-form-item>
							<el-button type='primary' icon='el-icon-search' @click='search'>
								搜索
							</el-button>
						</el-form-item>
						<el-form-item>
							<el-button type='导出' icon='el-icon-search' @click='exportExcel'>
								导出
							</el-button>
						</el-form-item>
					</div>
				</el-form>
			</div>
			<el-tabs v-model="activeTabName" @tab-click="tabClick">
				<el-tab-pane label="全部" name="all"></el-tab-pane>
				<el-tab-pane label="待付款" name="status_0"></el-tab-pane>
				<el-tab-pane label="待发货" name="status_1"></el-tab-pane>
				<el-tab-pane label="待收货" name="status_2"></el-tab-pane>
				<el-tab-pane label="待评价" name="status_3"></el-tab-pane>
				<el-tab-pane label="已完成" name="status_4"></el-tab-pane>
				<el-tab-pane label="已关闭" name="status_5"></el-tab-pane>
			</el-tabs>

			<el-card v-loading='is_loading'>
				<el-table :data="list" border style="width: 100%">
					<el-table-column label="商品" width="400">
						<template slot-scope='scope'>
							<div v-for="(item, index) in scope.row.goods_list" class="goods-item">
								<div class=" flex-row" :class="[index > 0 ? 'border-bottom' : '']">
									<div>
										<el-image :src='item.goods.cover_pic' style="width: 80px;height: 80px;">
										</el-image>
									</div>
									<div style="padding: 10px;">
										<div>{{ item.goods.name }}</div>
										<div>x {{ item.num }}</div>
									</div>
								</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column label="订单信息" width="400">
						<template slot-scope='scope'>
							<div>订单ID：{{ scope.row.id }}</div>
							<div>创建时间：{{ scope.row.created_at }}</div>
							<div class="flex-row"><span>
									<a v-clipboard:copy="scope.row.order_no" v-clipboard:success="onCopy"
										v-clipboard:error="onError">订单号：{{ scope.row.order_no }}
										<span class="el-button--text" style="padding-left:3px;">复制</span>
									</a>
								</span>
							</div>
							<div>收件人：{{ scope.row.name }} {{ scope.row.mobile }}</div>
							<div>收货地址：{{ scope.row.address }}</div>
						</template>
					</el-table-column>

					<el-table-column label="买家">
						<template slot-scope='scope'>
							<div class="flex-row" style="font-size: 10px;">
								<div>
									<div>
										<el-avatar size="medium" :src="scope.row.avatar_url"></el-avatar>
									</div>
									<div>(ID: {{ scope.row.user_id }})</div>
								</div>
								<div style="padding: 0 10px;">
									<div>{{ scope.row.nickname }}</div>
									<div>
										{{ scope.row.mobile }}
									</div>
								</div>
							</div>
						</template>
					</el-table-column>
					
					<el-table-column label="是否申请发货">
						<template slot-scope="scope">
							<div v-if="scope.row.is_apply_send == 1">
								<div>
									<el-tag type="danger">是</el-tag>
								</div>
								<div>申请时间：{{ scope.row.apply_send_time }}</div>
							</div>
					
							<el-tag v-else>否</el-tag>
						</template>
					</el-table-column>
					<el-table-column label="实际付款">
						<template slot-scope='scope'>
							<div style="font-weight: bold;">
								￥{{ scope.row.pay_price }}
								<br>
								(含运费：￥{{ scope.row.express_price }})
							</div>
						</template>
					</el-table-column>
					<el-table-column label="支付配送">
						<template slot-scope='scope'>
							<div>
								<div v-if="scope.row.is_pay == 1">
									<div v-if="scope.row.pay_type == 0" class="flex-y-center">
										<el-image :src="scope.row.pay_type_logo"
											style="width: 20px;height:20px;margin-right: 5px;"></el-image>
										<span>微信支付</span>
									</div>
									<div v-if="scope.row.pay_type == 1" class="flex-y-center">
										<el-image :src="scope.row.pay_type_logo"
											style="width: 20px;height:20px;margin-right: 5px;"></el-image>
										<span>余额支付</span>
									</div>
									<div v-if="scope.row.pay_type == 2" class="flex-y-center">
										<el-image :src="scope.row.pay_type_logo"
											style="width: 20px;height:20px;margin-right: 5px;"></el-image>
										<span>系统支付</span>
									</div>
								</div>
								<div v-if="scope.row.is_pay == 0">
									<el-button type="text" style="color: #D39745;">未支付</el-button>
								</div>
								<br>
								<div v-if="scope.row.send_type == 0">
									快递
								</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column label="操作">
						<template slot-scope='scope'>
							<div class="operation">
								<span v-if="scope.row.status == 0" class="status">
									待付款
								</span>
								<span v-if="scope.row.status == 1" class="status">
									待发货
								</span>
								<span v-if="scope.row.status == 2" class="status">
									待收货
								</span>
								<span v-if="scope.row.status == 3" class="status">
									待评价
								</span>
								<span v-if="scope.row.status == 4" class="status">
									已完成
								</span>
								<span v-if="scope.row.status == 5" class="status">
									已关闭
								</span>
								<br>
								<div style="padding-top: 20px;">
									<span type='text' class="btn el-button--text" v-if="scope.row.status == 2"
										@click="confirm(scope.row)">确认收货</span>
									<span type='text' class="btn el-button--text" v-if="scope.row.status == 3"
										@click="finish(scope.row)">完成订单</span>
									<span type='text' class="btn el-button--text"
										v-if="scope.row.status == 0 || scope.row.status == 1"
										@click="cancel(scope.row)">取消订单</span>
									<span type='text' class="btn el-button--text">
										<router-link :to="'/order/detail?id=' + scope.row.id">订单详情</router-link>
									</span>
									<!-- <span type='text' class="btn el-button--text">小票打印</span> -->
								</div>
							</div>
						</template>
					</el-table-column>
				</el-table>


			</el-card>


			<div style="text-align: right;margin: 20px 0;">

				<pagination :pagination="pagination" v-model="page" @pageChange="getList"></pagination>
				<!-- 			<el-pagination v-if="list.length > 0" background :page-size="pagination.pageSize" @current-change="pageChange"
                     layout="prev, pager, next" :current-page="pagination.current_page" :total="pagination.total_count">
                    </el-pagination> -->
			</div>

		</el-card>
	</div>
</template>

<script>
import {
	export_json_to_excel
} from '@/utils/Export2Excel.js'

export default {
	name: 'order-index',
	data() {
		return {
			keyword: '',
			payment_name: '选择支付方式',
			keyword_display_name: '订单编号',
			keyword_type: 'order_no',
			activeTabName: 'all',
			search_date: [],
			is_loading: false,
			page: 1,
			list: [],
			status: -1,
			pagination: null,
			list: [],
			pay_type: '',
			user_id: '',
			parent_id: ''
		}
	},
	created() {
		if (this.$route.query.user_id) {
			this.user_id = this.$route.query.user_id
		}
	},
	mounted() {
		this.getList()
	},
	methods: {
		formatJson(filterVal, jsonData) {
			return jsonData.map(v => filterVal.map(j => v[j]))
		},
		exportExcel() {
			require.ensure([], () => {
				const tHeader = ['订单ID', '订单号', '买家', '商品明细', '实际支付', '订单总额', '省', '市', '区', '镇', '收货人', '电话',
					'收货地址', '下单时间', '付款时间', '发货时间', '订单类型', '状态'
				]
				// 上面设置Excel的表格第一行的标题
				const filterVal = ['id', 'order_no', 'nickname', 'goods_detail', 'pay_price', 'total_price',
					'province', 'city', 'county', 'town', 'name', 'mobile', 'address', 'created_at',
					'pay_time', 'send_at', 'order_type', 'status'
				]
				// 上面的index、nickName、name是tableData里对象的属性
				this.$request({
					url: '/mall/order/export',
					data: {
						page: this.page,
						status: this.status,
						keyword: this.keyword,
						keyword_type: this.keyword_type,
						search_date: this.search_date,
						pay_type: this.pay_type,
						user_id: this.user_id,
						parent_id: this.parent_id
					}
				}).then(res => {
					if (res.code == 0) {
						let list = res.data.list
						const data = this.formatJson(filterVal, list)
						export_json_to_excel(tHeader, data, '订单数据')
					}
				})
			})
		},
		keywordChange(e) {
			this.keyword_type = e
			if (e == 'order_no') {
				this.keyword_display_name = '订单编号'
			}
			if (e == 'user_id') {
				this.keyword_display_name = '用户ID'
			}
			if (e == 'nickname') {
				this.keyword_display_name = '用户昵称'
			}

			if (e == 'name') {
				this.keyword_display_name = '收件人信息'
			}
			if (e == 'address') {
				this.keyword_display_name = '收件地址'
			}
			if (e == 'goods_name') {
				this.keyword_display_name = '商品名称'
			}

		},
		paymentChange(e) {
			this.pay_type = e
			if (e == 'not_pay') {
				this.payment_name = '未支付'
			}
			if (e == 'wechat_pay') {
				this.payment_name = '微信支付'
			}
			if (e == 'sys_pay') {
				this.payment_name = '后台支付'
			}
			if (e == 'balance_pay') {
				this.payment_name = '余额支付'
			}
		},
		tabClick() {

			if (this.activeTabName == 'status_0') {
				this.status = 0
			}
			if (this.activeTabName == 'status_1') {
				this.status = 1
			}
			if (this.activeTabName == 'status_2') {
				this.status = 2
			}
			if (this.activeTabName == 'status_3') {
				this.status = 3
			}
			if (this.activeTabName == 'status_4') {
				this.status = 4
			}
			if (this.activeTabName == 'status_5') {
				this.status = 5
			}
			if (this.activeTabName == 'all') {
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
				url: '/mall/order/list',
				data: {
					page: this.page,
					status: this.status,
					keyword: this.keyword,
					keyword_type: this.keyword_type,
					search_date: this.search_date,
					pay_type: this.pay_type,
					user_id: this.user_id,
					parent_id: this.parent_id
				}
			}).then(res => {
				this.is_loading = false
				if (res.code == 0) {
					this.list = res.data.list
					this.pagination = res.data.pagination
				}
			})
		},
		onCopy(e) { // 复制成功
			this.$message({
				message: '复制成功！',
				type: 'success'
			})
		},
		onError(e) { // 复制失败
			this.$message({
				message: '复制失败！',
				type: 'error'
			})
		},
		pageChange(e) {

			this.page = e
			this.getList()
		},
		confirm(row) {
			this.$confirm('是否确认收货？', '提示', {}).then(() => {
				this.$request({
					url: '/mall/order/confirm',
					data: {
						order_id: row.id
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg)
						this.getList()
					}
				})
			})
		},
		finish(row) {
			this.$confirm('是否完成订单', '提示', {}).then(() => {

				this.$request({
					url: '/mall/order/finish',
					data: {
						order_id: row.id
					},
					method: 'post'
				}).then(res => {

					this.$message.success(res.msg)
					this.getList()
				})
			}).catch(() => { })
		},
		cancel(row) {
			this.$confirm('确认取消订单？', '提示', {}).then(() => {
				this.$request({
					url: '/mall/order/close',
					data: {
						order_id: row.id
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg)
						this.getList()
					}
				})
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

.btn:hover {
	cursor: pointer;
}

.goods-item {
	.border-bottom {
		border-top: solid #f0f0f0 1px;
		margin-top: 10px;
		padding-top: 10px;
	}
}

.operation {
	.status {
		font-weight: 600;
		color: #FFA300;
		font-size: large;

	}
}
</style>
