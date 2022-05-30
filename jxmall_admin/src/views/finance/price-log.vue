<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>佣金记录</span>
				<!-- <el-button style="float: right; padding: 3px 0" type="text"> </el-button> -->
			</div>
			<el-form ref="searchForm"  :inline="true" label-width="68px">
				<el-form-item label="用户昵称">
					<el-input v-model='nickname' placeholder="用户昵称">
					</el-input>
				</el-form-item>
				<el-form-item label="用户ID">
					<el-input v-model='user_id' placeholder="用户ID">
					</el-input>
				</el-form-item>
				<el-form-item label="关键词">
					<el-input v-model='keyword' placeholder="关键词">
					</el-input>
				</el-form-item>
				<el-form-item label="创建时间">
					<el-date-picker value-format="yyyy-MM-dd HH:mm:ss" v-model="search_date" type="datetimerange"
						range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
					</el-date-picker>
				</el-form-item>
				<el-form-item>
					<el-button  type='primary' style="margin-left: 10px;" icon='el-icon-search' @click='search'>
						搜索
					</el-button>
					<el-button   type='primary' style="margin-left: 10px;" icon='el-icon-search' @click='exportExcel'>
						导出
					</el-button>
				</el-form-item>
			</el-form>

			<div>
				<el-table :data="list" style="width: 100%" v-loading="is_loading">
					<el-table-column prop="id" label="ID" width="50px">
					</el-table-column>
					<el-table-column label="用户信息">
						<template slot-scope="scope">
							<div class="flex-y-center">
								<el-avatar :src="scope.row.avatar_url"></el-avatar>
								<div style="padding: 0 10px;">{{scope.row.nickname}}({{scope.row.user_id}})</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column label="佣金" prop="price">
					</el-table-column>

					<el-table-column prop="remarks" label="备注">
					</el-table-column>
					<el-table-column prop="created_at" label="创建时间">
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
				is_loading: false,
				page: 1,
				keyword: '',
				user_id: '',
				pagination: '',
				content: '',
				nickname: '',
				search_date: [],
				list: [],
			}
		},
		created() {
			this.getList()
		},
		methods: {
			exportExcel() {
				const header = ['ID', '用户昵称', '创建时间', '原因', '佣金'];
				// 上面设置Excel的表格第一行的标题
				const fields = ['id', 'nickname', 'created_at', 'remarks', 'price'];
				// 上面的index、nickName、name是tableData里对象的属性
				this.$request({
					url: "/mall/finance/price-log",
					data: {
						keyword: this.keyword,
						search_date: this.search_date,
						user_id: this.user_id,
						nickname: this.nickname,
						is_export: 1,
					}
				}).then(res => {
					if (res.code == 0) {
						let list = res.data.list;

						this.$export(header, fields, list, '佣金记录');
					}
				})
			},
			search() {
				this.list = [];
				this.page = 1;
				this.pagination = null;
				this.getList();
			},
			getList() {
				this.is_loading = true;
				this.$request({
					url: "/mall/finance/price-log",
					data: {
						page: this.page,
						keyword: this.keyword,
						user_id: this.user_id,
						nickname: this.nickname,

						search_date: this.search_date,
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
