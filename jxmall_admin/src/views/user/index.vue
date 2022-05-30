<template>

	<div class="app-container">
		<el-card class="box-card" v-loading="is_loading">
			<div slot="header" class="clearfix">
				<span>用户管理</span>
			</div>
			<div>
				<el-form ref="searchForm"  :inline="true" label-width="100px">
					<el-form-item label="用户ID">
						<el-input v-model="search_user_id" placeholder="请输入用户ID">
						</el-input>
					</el-form-item>
					<el-form-item label="用户昵称">
						<el-input v-model="nickname" placeholder="请输入用户昵称">
						</el-input>
					</el-form-item>
					<el-form-item label="推荐人用户ID">
						<el-input v-model="parent_id" placeholder="请输入推荐人用户ID">
						</el-input>
					</el-form-item>
					<el-form-item label="团队长用户ID">
						<el-input v-model="top_parent_id" placeholder="请输入团队长用户ID">
						</el-input>
					</el-form-item>
					<el-form-item label="等级ID">
						<el-input  v-model="level_id" placeholder="请输入等级ID">
						</el-input>
					</el-form-item>
					<el-form-item label="手机号">
						<el-input v-model="mobile" placeholder="请输入用户手机号">
						</el-input>
					</el-form-item>
					<el-form-item>
						<el-button type="primary" @click="search">搜索</el-button>
					</el-form-item>
				</el-form>

				<el-table :data="list" style="width: 100%;">
					<el-table-column prop="id" label="用户ID" width="100">
					</el-table-column>
					<el-table-column label="用户">
						<template slot-scope="scope">
							<el-popover style="border: none;" placement="right-start" width="200" trigger="hover">
								<div style="font-size: 10px;">
									<div class="flex-y-center" style="display: flex;">
										<div style="width: 70px;text-align: end;padding-right: 10px;">ID:</div>
										<div>{{scope.row.id}}</div>
									</div>
									<div class="flex-y-center" style="display: flex;">
										<div style="width: 70px;text-align: end;padding-right: 10px;">手机号:</div>
										<div>
											{{scope.row.mobile}}
										</div>
									</div>
									<div class="flex-y-center" style="display: flex;">
										<div style="width: 70px;text-align: end;padding-right: 10px;">推荐人ID:</div>
										<div>{{scope.row.parent_id}}</div>
									</div>
									<div class="flex-y-center" style="display: flex;">
										<div style="width: 70px;text-align: end;padding-right: 10px;">推荐人:</div>
										<div class="flex-y-center">
											<div>
												<el-avatar size="small" :src="scope.row.parent_avatar"></el-avatar>
											</div>
											<div>
												{{scope.row.parent_name}}
											</div>
										</div>
									</div>
									<div class="flex-y-center" style="display: flex;">
										<div style="width: 70px;text-align: end;padding-right: 10px;">状态:</div>
										<div>正常</div>
									</div>
									<div class="flex-y-center" style="display: flex;">
										<div style="width: 70px;text-align: end;padding-right: 10px;">关注状态:</div>
										<div>{{scope.row.is_subscribe==1?'已关注':'未关注'}}</div>
									</div>
								</div>
								<div slot="reference" class="flex-row">
									<el-avatar size="large" :src="scope.row.avatar_url"></el-avatar>
									<div style="padding-left: 10px;">
										<div>{{scope.row.nickname}}</div>
										<div class="flex-y-center">
											<div>
												<el-avatar style="background: none;" size="small"
													:src="scope.row.platform_logo"></el-avatar>
											</div>
											<div v-if="scope.row.mobile">
												{{scope.row.mobile}}
											</div>
										</div>
									</div>
								</div>
							</el-popover>
						</template>
					</el-table-column>
					<el-table-column label="推广资格">
						<template slot-scope="scope">
							<div v-if="scope.row.is_inviter==0"><span style="color: #FF9952;">否</span>
							</div>
							<div v-else><span style="color: #3CB062;">是</span>
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="level" label="会员等级">
					</el-table-column>
					<el-table-column prop="level_at" label="升级时间">
						<template slot-scope="scope">
							<div v-if="scope.row.level_at!=0"><span
									style="color: #FF9952;">{{scope.row.level_at}}</span>
							</div>
							<div v-else><span style="color: #FF9952;">-</span>
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="parent_id" label="推荐人ID">
						<template slot-scope="scope">
							<div v-if="scope.row.parent_id!=0"><span
									style="color: #FF9952;">{{scope.row.parent_id}}</span>
							</div>
							<div v-else><span style="color: #FF9952;">无推荐人</span>
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="created_at" label="注册时间">
					</el-table-column>
					<el-table-column label="积分/余额">
						<template slot-scope="scope">
							<div>积分：<span style="color: #FF9952;">{{scope.row.integral}}</span></div>
							<div>余额：<span style="color: #FF9952;">{{scope.row.money}}</span></div>
						</template>
					</el-table-column>
					<el-table-column label="成交">
						<template slot-scope="scope">
							<div>订单：<span style="color: #FF9952;">{{scope.row.order_num}}</span></div>
							<div>金额：<span
									style="color: #FF9952;">{{scope.row.total_order_price?scope.row.total_order_price:"0.00"}}</span>
							</div>
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
							<el-button type="text" @click="edit(scope.row.id)">编辑</el-button>
							<el-button type="text" @click="order(scope.row.id)">订单</el-button>
						</template>
					</el-table-column>
				</el-table>
				<div style="text-align: right;margin: 20px 0;">
					<pagination :pagination="pagination" @pageChange="getList" v-model="page"></pagination>
				</div>
			</div>
		</el-card>

	</div>
</template>

<script>
	export default {
		name: 'user-index',
		data() {
			return {
				list: [],
				page: 1,
				is_loading: false,
				pagination: null,
				search_user_id: '',
				nickname: '',
				mobile: '',
				parent_id: '',
				level_id: '',
				top_parent_id: '',
			}
		},
		created() {
			this.getList();

		},
		methods: {
			search() {
				this.page = 1;
				this.list = [];
				this.pagination = null;
				this.list = [];
				this.getList();

			},
			edit(id) {
				this.$router.push({
					path: '/user/edit',
					query: {
						id: id
					},

				})
			},
			order(id) {
				this.$router.push({
					path: '/order/index',
					query: {
						user_id: id
					},
				})
			},
			getList() {
				this.is_loading = true;

				this.$request({
					url: '/mall/user/index',
					data: {
						page: this.page,
						user_id: this.search_user_id,
						nickname: this.nickname,
						mobile: this.mobile,
						parent_id: this.parent_id,
						top_parent_id: this.top_parent_id,
						level_id: this.level_id
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list
						this.pagination = res.data.pagination
					}
				})

			},
		}
	}
</script>

<style>
</style>
