<template>
	<div>
		<el-dialog title="选择用户" :visible.sync="dialogVisible" width="60%" :before-close="handleClose">

			<div>
				<div class="flex-row">
					<el-input style="width: 250px;" v-model="keyword" placeholder="请输入用户昵称进行搜索"></el-input>
					<el-button type="primary" style="margin: 0 10px;" @click="searchUser">搜索</el-button>
				</div>
				<scroll-bar :height="400">
					<el-table :data="list" style="width: 100%" v-loading="is_loading">
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
												{{scope.row.mobile}}</div>
										</div>
										<div class="flex-y-center" style="display: flex;">
											<div style="width: 70px;text-align: end;padding-right: 10px;">推荐人ID:</div>
											<div>{{scope.row.parent_id}}</div>
										</div>
										<div class="flex-y-center" style="display: flex;">
											<div style="width: 70px;text-align: end;padding-right: 10px;">推荐人:</div>
											<div class="flex-y-center">
												<el-avatar size="small" :src="scope.row.parent_avatar"></el-avatar>
												<div>
													{{scope.row.parent_name}}
												</div>
											</div>
										</div>
										<div class="flex-y-center" style="display: flex;">
											<div style="width: 70px;text-align: end;padding-right: 10px;">状态:</div>
											<div>正常</div>
										</div>
									</div>
									<div slot="reference" class="flex-row">
										<el-avatar size="large" :src="scope.row.avatar_url"></el-avatar>
										<div style="padding-left: 10px;">
											<div>{{scope.row.nickname}}</div>
											<div>
												<el-avatar style="background: none;" size="small" :src="scope.row.platform_logo"></el-avatar>
											</div>
										</div>
									</div>
								</el-popover>
							</template>
						</el-table-column>
						<el-table-column prop="level" label="会员等级">
						</el-table-column>
						<el-table-column prop="created_at" label="注册时间">
						</el-table-column>

						<el-table-column label="成交">
							<template slot-scope="scope">
								<div>订单：<span style="color: #FF9952;">{{scope.row.order_num}}</span></div>
								<div>金额：<span style="color: #FF9952;">{{scope.row.total_order_price}}</span></div>
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
								<el-button type="text" v-if="!scope.row.is_checked" @click="selectUser(scope.$index,scope.row.id)">选择</el-button>
								<el-button type="primary" size="mini" v-if="scope.row.is_checked">已选</el-button>
							</template>
						</el-table-column>
					</el-table>

				</scroll-bar>
				<div style="text-align: right;margin: 20px 0;">
					<pagination :pagination="pagination" @pageChange="getList" v-model="page"></pagination>
				</div>
			</div>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="confirmSelect">确 定</el-button>
			</span>
		</el-dialog>


	</div>
</template>

<script>
	export default {
		props: {
			show: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				pagination: null,
				is_loading: false,
				page: 1,
				list: [],
				dialogVisible: false,
				checked_list: [],
				checked_ids: [],
				keyword: '',

			};
		},
		watch: {

			show(newVal, oldVal) {
				if (newVal) {
					this.dialogVisible = true;
					this.page = 1;
					this.list = [];
					this.getList();
				} else {
					this.dialogVisible = false;
				}
			},
			dialogVisible(newVal, oldVal) {
				if (!this.dialogVisible) {
					this.$emit('close')
					this.list = [];
					this.checked_ids = [];
					this.checked_list = [];
				}
			}
		},
		created() {
		
		},
	
		methods: {
			searchUser() {
				this.page = 1;
				this.list = [];
				this.select_user_id = 0;
				this.select_user_index = -1;
				this.getList();
			},
			selectUser(index, user_id) {
				let list = JSON.parse(JSON.stringify(this.list))
				let row = this.list[index];
				list[index].is_checked = true;
				this.list = list;
				this.checked_list.push(row);
				this.checked_ids.push(user_id);
				this.$forceUpdate();
			},
			confirmSelect() {
				if (this.checked_ids.length == 0) {
					this.$message.warning('请选择用户');
					return;
				}
				this.dialogVisible = false;
				this.$emit('selected', {
					checked_ids: this.checked_ids,
					checked_list: this.checked_list,
				});



			},

			handleClose(e) {
				this.dialogVisible = false;
			},

			getList() {
				this.is_loading = true;
				this.$request({
					url: "/mall/user/index",
					data: {
						page: this.page,
						keyword: this.keyword
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						let list = res.data.list;
						if (this.checked_ids.length) {
							list.forEach((item) => {
								if (this.checked_ids.includes(item.id)) {
									item.is_checked = true;
								}
							})
						}
						this.list = list;
						this.pagination = res.data.pagination;
					}
				})

			},
		}

	}
</script>


<style>
</style>
