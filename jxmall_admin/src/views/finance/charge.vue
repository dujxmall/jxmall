<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>后台充值记录</span>
				<!-- <el-button style="float: right; padding: 3px 0" type="text"> </el-button> -->
			</div>

			<div>
				<el-tabs v-model="activeName" @tab-click="handleClick">
					<el-tab-pane label="全部" name="all"></el-tab-pane>
					<el-tab-pane label="余额" name="balance"></el-tab-pane>
					<el-tab-pane label="积分" name="integral"></el-tab-pane>
				</el-tabs>
				<el-table :data="list" style="width: 100%" v-loading="is_loading">
					<el-table-column prop="id" label="ID" width="50px">
					</el-table-column>
					<el-table-column label="用户信息">
						<template slot-scope="scope">
							<div class="flex-y-center">
								<el-avatar :src="scope.row.avatar_url"></el-avatar>
								<div style="padding: 0 10px;">{{scope.row.nickname}}</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column label="充值类型">
						<template slot-scope='scope'>
							<div v-if="scope.row.type==0">余额充值</div>
							<div v-if="scope.row.type==1">积分充值</div>
						</template>
					</el-table-column>
					<el-table-column prop="admin" label="操作人员">
					</el-table-column>
					<el-table-column prop="num" label="金额">
					</el-table-column>
					<el-table-column prop="before_num" label="充值之前">
					</el-table-column>
					<el-table-column prop="after_num" label="充值之后">
					</el-table-column>
					<el-table-column prop="remarks" label="备注">
					</el-table-column>
					<el-table-column prop="created_at" label="操作时间">
					</el-table-column>
				</el-table>
				<pagination :pagination="pagination" v-model="page" @pageChange="getList"></pagination>
			</div>

		</el-card>

	</div>
</template>

<script>
	export default {

		data() {

			return {
				activeName: 'all',

				is_loading: false,
				page: 1,
				keyword: '',
				user_id: '',
				pagination: null,
				list: [],
				type: ''
			}
		},
		created() {
			this.getList()
		},
		methods: {
			handleClick(e) {
				if (e.name == 'all') {
					this.type = '';
					this.activeName = 'all'
				}
				if (e.name == 'balance') {
					this.type = 0;
					this.activeName = 'balance'
				}
				if (e.name == 'integral') {
					this.type = 1;
					this.activeName = 'integral'
				}
				this.page = 1;
				this.list = [];
				this.getList();
			},
			getList() {
				this.is_loading = true;
				this.$request({
					url: "/mall/finance/charge",
					data: {
						page: this.page,
						keyword: this.keyword,
						user_id: this.user_id,
						type: this.type
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list;
						this.pagination = res.data.pagination
					}
				}).catch(e => {
					this.is_loading = false;
				})
			},

		}

	}
</script>

<style>
</style>
