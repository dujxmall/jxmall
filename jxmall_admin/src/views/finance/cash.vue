<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>佣金提现</span>
			</div>
			<el-form ref="searchForm" label-width="68px" :inline="true">
				<el-form-item label="用户昵称">
					<el-input v-model='nickname' placeholder="用户昵称" style="width: 300px;margin-right: 10px;">
					</el-input>
				</el-form-item>

				<el-form-item label="团队长ID">
					<el-input v-model='parent_id' placeholder="团队长ID" style="width: 300px;margin-right: 10px;">
					</el-input>
				</el-form-item>
				<el-form-item label="申请时间">
					<el-date-picker value-format="yyyy-MM-dd HH:mm:ss" v-model="search_date" type="datetimerange"
						style="width: 300px;" range-separator="至" start-placeholder="提现申请时间" end-placeholder="提现申请时间">
					</el-date-picker>
				</el-form-item>
				<el-form-item label="审核状态">
					<el-radio v-model="status" label="-1">全部</el-radio>
					<el-radio v-model="status" label="0">待审核</el-radio> 
					<el-radio v-model="status" label="1">已审核</el-radio> 
					<el-radio v-model="status" label="2">已驳回</el-radio>
				</el-form-item>
				<el-form-item label="是否打款">
					<el-radio v-model="is_price" label="-1">全部</el-radio> 
					<el-radio v-model="is_price" label="0">待打款</el-radio>
					<el-radio v-model="is_price" label="1">已打款</el-radio>
				</el-form-item>
				<el-form-item label="打款方式">
					<el-radio v-model="cash_type" label="all">全部</el-radio> 
					<el-radio v-model="cash_type" label="balance">打款到余额</el-radio>
					<el-radio v-model="cash_type" label="wechat">打款到微信</el-radio>
					<el-radio v-model="cash_type" label="bank">打款到银行卡</el-radio>
				</el-form-item>


				<el-form-item>
					<el-button type='primary' style="margin-left: 10px;" icon='el-icon-search' @click='search'>搜索
					</el-button>
				</el-form-item>
				<!--     <el-button type='primary' style="margin-left: 10px;" icon='el-icon-search' @click='exportExcel'>导出
        </el-button> -->
			</el-form>

			<el-tabs v-model="activeName" @tab-click="handleClick">
				<el-tab-pane label="全部" name="all"></el-tab-pane>
				<el-tab-pane label="佣金提现" name="price"></el-tab-pane>
				<el-tab-pane label="余额提现" name="balance"></el-tab-pane>
			</el-tabs>

			<el-table :data="list" style="width: 100%" v-loading="is_loading">
				<el-table-column prop="id" label="ID" width="100px">
				</el-table-column>
				<el-table-column label="用户信息">
					<template slot-scope="scope">
						<div class="flex-y-center">
							<el-avatar :src="scope.row.avatar_url"></el-avatar>
							<div style="padding: 0 10px;">
								{{scope.row.nickname}}(用户ID:{{scope.row.user_id}})
							</div>
						</div>
					</template>
				</el-table-column>

				<el-table-column label="提现金额" prop="cash_price" width="80px">
				</el-table-column>
				<el-table-column label="服务费" prop="service_price" width="80px">
				</el-table-column>
				<el-table-column prop="price" label="实际打款" width="80px">
				</el-table-column>
				<el-table-column label="提现时间" prop="created_at">
				</el-table-column>
				<el-table-column label="提现类型">
					<template slot-scope="scope">
						<span v-if="scope.row.type==0">
							佣金提现
						</span>
						<span v-if="scope.row.price_type==1">
							余额提现
						</span>
					</template>
				</el-table-column>
				<el-table-column label="打款方式">
					<template slot-scope="scope">
						<span v-if="scope.row.cash_type=='wechat'">
							提现到微信
						</span>
						<span v-if="scope.row.cash_type=='balance'">
							提现到余额
						</span>
						<span v-if="scope.row.cash_type=='bank'">
							提现到银行卡
						</span>
						<span v-if="scope.row.cash_type=='alipay'">
							提现到支付宝
						</span>
						<div v-if="scope.row.is_price==1">
							<span v-if="scope.row.is_manual==1">
								<el-tag type="warning">手动打款</el-tag>
							</span>
						</div>

					</template>
				</el-table-column>
				<el-table-column label="审核状态">
					<template slot-scope="scope">
						<span v-if="scope.row.status==0">
							待处理
						</span>
						<span v-if="scope.row.status==1">
							<el-tag type="success">通过</el-tag>
						</span>
						<span v-if="scope.row.status==2">
							<el-tag type="danger">拒绝</el-tag>
						</span>
					</template>
				</el-table-column>
				<el-table-column label="打款状态">
					<template slot-scope="scope">
						<span v-if="scope.row.is_price==0">
							未打款
						</span>
						<span v-if="scope.row.is_price==1">
							<el-tag type="success">已打款</el-tag>

						</span>
					</template>
				</el-table-column>
				<el-table-column label="打款凭证">
					<template slot-scope="scope">
						<span v-if="!scope.row.receipt">
							<file-picker :multiple='false' @selected="receiptPic" v-model="scope.row.receipt"
								:is_site="1">
								<el-button type="text" style="margin: 0 10px;" @click="uploadReceipt(scope.$index)">
									上传打款凭证
								</el-button>
							</file-picker>
						</span>
						<span v-if="scope.row.receipt">
							<el-image style="width: 100px; height: 100px" :src="scope.row.receipt"
								:preview-src-list="[scope.row.receipt]">
							</el-image>
						</span>
					</template>
				</el-table-column>
				<el-table-column label="驳回原因" prop="reason"></el-table-column>
				<el-table-column label="其他信息">
					<template slot-scope="scope">
						<span v-if="scope.row.cash_type=='wechat'">
							微信号：{{scope.row.account}}
						</span>
						<span v-if="scope.row.cash_type=='bank'">
							开户人姓名：{{scope.row.name}}
							<br>
							开户人行：{{scope.row.bank_name}}
							<br>
							账号：{{scope.row.account}}
						</span>

						<span v-if="scope.row.cash_type=='alipay'">
							支付宝户名：{{scope.row.name}}
							<br>

							账号：{{scope.row.account}}
						</span>
					</template>
				</el-table-column>
				<el-table-column label="操作">
					<template slot-scope="scope">
						<div v-if="scope.row.status==0">
							<el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info"
								iconColor="red" :title="'确定通过审核?'" @onConfirm="handleApply(scope.row.id,1)">
								<el-button type="text" slot="reference" @click="">通过审核</el-button>
							</el-popconfirm>
							<el-popconfirm style="padding: 0 10px;" confirmButtonText='确定' cancelButtonText='取消'
								icon="el-icon-info" iconColor="red" :title="'确定通过驳回?'"
								@onConfirm="showReason(scope.row.id)">
								<el-button type="text" slot="reference">驳回</el-button>
							</el-popconfirm>
						</div>
						<div style="padding: 0 10px;" v-if="scope.row.status==1&&scope.row.is_price==0">
							<el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info"
								iconColor="red" :title="'是否确定打款?'" @onConfirm="handlePay(scope.row.id,1)">
								<el-button type="text" slot="reference" @click="">确认打款</el-button>
							</el-popconfirm>
							<div v-if="scope.row.cash_type=='wechat'">
								<el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info"
									iconColor="red" :title="'确定使用线下打款?'" @onConfirm="handleManualPay(scope.row.id,1)">
									<el-button type="text" slot="reference">线下打款</el-button>
								</el-popconfirm>
							</div>
						</div>
						<div v-if="scope.row.is_price==1">
							<el-button type="text">已打款</el-button>
						</div>


						<div v-if="scope.row.status==2">
							<el-button type="text">已驳回</el-button>
						</div>

					</template>
				</el-table-column>
			</el-table>

			<pagination v-model="page" @pageChange="getList" :pagination="pagination"></pagination>
			<el-dialog title="输入驳回原因" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
				<el-input type="textarea" v-model="reason"></el-input>
				<span slot="footer" class="dialog-footer">
					<el-button @click="dialogVisible = false">取 消</el-button>
					<el-button type="primary" @click="submit">确 定</el-button>
				</span>
			</el-dialog>
		</el-card>

	</div>

</template>

<script>
	export default {

		data() {
			return {
				is_loading: false,
				page: 1,
				activeName: 'all',
				list: [],
				type: -1,
				pagination: null,
				row_index: -1,
				keyword: '',
				user_id: '',
				pagination: '',
				nickname: '',
				search_date: [],
				is_price: '-1',
				status: '-1',
				parent_id: '',
				cash_type: 'all',
				reason: '',
				dialogVisible: false,
				cash_id: '',
			}
		},
		created() {
			this.getList()
		},
		methods: {
			showReason(id) {
				this.cash_id = id;
				this.dialogVisible = true;
			},
			handleClose() {
				this.dialogVisible = false;
			},
			submit() {
				this.handleApply(this.cash_id, 2);

			},
			search() {
				this.list = [];
				this.page = 1;
				this.pagination = null;
				this.getList();
			},
			handleClick(e) {

				if (e.name == 'all') {
					this.type = -1;
				}
				if (e.name == 'price') {
					this.type = 0;
				}
				if (e.name == 'balance') {
					this.type = 1;
				}
				this.activeName = e.name;
				this.page = 1;
				this.getList();

			},
			receiptPic(e) {
				if (e.length == 0) {
					return;
				}
				let row = this.list[this.row_index];
				this.$request({
					url: "/mall/finance/upload-receipt",
					data: {
						id: row.id,
						receipt: e[0].url
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg);
						this.getList();
					}
				})

			},
			uploadReceipt(index) {
				this.row_index = index;
			},
			getList() {
				this.is_loading = true;
				this.$request({
					url: "/mall/finance/cash-list",
					data: {
						cash_type: this.cash_type,
						page: this.page,
						type: this.type,
						user_id: this.user_id,
						nickname: this.nickname,
						search_date: this.search_date,
						status: this.status,
						is_price: this.is_price,
						parent_id: this.parent_id
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list;
						this.pagination = res.data.pagination;

					}
				}).catch(e => {
					this.is_loading = false;
				})
			},
			handleApply(id, status) {
				if (status == 2) {
					if (!this.reason) {
						this.$message.warning('请输入驳回原因');
						return;
					}
				}
				this.$request({
					url: "/mall/finance/handle-apply",
					data: {
						id: id,
						status: status,
						reason: this.reason
					},
					method: 'post'
				}).then(res => {
					this.handleClose();
					if (res.code == 0) {
						this.$message.success(res.msg)
						this.getList();
					}
				})
			},
			handlePay(id, status) {
				this.$request({
					url: "/mall/finance/handle-pay",
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
			handleManualPay(id, status) {
				this.$request({
					url: "/mall/finance/manual-pay",
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
			}
		}
	}
</script>

<style>
</style>
