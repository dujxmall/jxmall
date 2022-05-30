<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>手动发券</span>
				<!-- <el-button style="float: right; padding: 3px 0" type="text"> </el-button> -->
			</div>

			<el-form ref="searchForm" :inline="true" label-width="68px">
				<el-form-item label="用户昵称">
					<el-input v-model='keyword' placeholder="用户昵称" style="width: 250px;">
					</el-input>
				</el-form-item>
				<el-form-item label="用户ID">
					<el-input v-model='user_id' placeholder="用户ID" style="width: 250px;">
					</el-input>
				</el-form-item>
				<el-form-item label="生日">
					<el-date-picker value-format="yyyy-MM-dd" v-model="search_date" type="daterange" range-separator="至"
						start-placeholder="开始日期" end-placeholder="结束日期">
					</el-date-picker>
				</el-form-item>
				<el-form-item label="按年搜索">
					<el-input v-model="year" placeholder="输入年" type="number" :max="12" :min="1" style="width: 200px;">
					</el-input>
				</el-form-item>
				<el-form-item label="按月搜索">
					<el-input v-model="month" placeholder="输入月" type="number" :max="12" :min="1" style="width: 200px;">
					</el-input>
				</el-form-item>
				<el-form-item label="按日搜索">
					<el-input placeholder="输入日" v-model="day" type="number" :max="31" :min="1" style="width: 200px;">
					</el-input>
				</el-form-item>
				<el-form-item label="注册时间">
					<el-date-picker value-format="yyyy-MM-dd HH:mm:ss" v-model="sign_time" type="datetimerange"
						range-separator="至" start-placeholder="开始时间" end-placeholder="结束时间">
					</el-date-picker>
				</el-form-item>
				<el-form-item>
					<el-button type='primary' style="margin-left: 10px;" icon='el-icon-search' @click='search'>搜索
					</el-button>
				</el-form-item>
				<el-form-item>
					<el-button type='danger' style="margin-left: 10px;" @click='send'>赠送
					</el-button>
				</el-form-item>
			</el-form>
			<el-table :data="list" style="width: 100%" ref="multipleTable" tooltip-effect="dark"
				@selection-change="handleSelectionChange" v-loading="is_loading">
				<el-table-column type="selection" width="55">
				</el-table-column>
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
				<el-table-column label="生日" prop="birthday">
				</el-table-column>
			</el-table>
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
				sign_time: [],
				list: [],
				month: '',
				year: '',
				day: '',
				integral: '',
				select_uids: [],
				coupon_id: '',

			}
		},
		created() {
			if (this.$route.query.id) {
				this.coupon_id = this.$route.query.id;
			} else {
				this.$message.error('请选择优惠券');
				this.$go.back();
			}
		},
		methods: {
			handleSelectionChange(e) {
			 
				this.select_uids = [];
				e.forEach(item => {
					this.select_uids.push(item.id);
				})
			},

			search() {
				this.list = [];
				this.getList();
			},
			send() {
				if (this.coupon_id == '' || !this.coupon_id) {
					this.$message.error('请先选择优惠券')
					return;
				}
				if (this.select_uids.length == 0) {
					this.$message.error('请选择操作对象')
					return;
				}

				this.$request({
					url: "/mall/market/coupon-send",
					data: {
						uids: this.select_uids,
						coupon_id: this.coupon_id,
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg);
						this.$go.back();
					}
				}).catch(res => {
					this.$message.warning(res.msg);
				})
			},

			getList() {
				this.is_loading = true;
				this.$request({
					url: "/mall/market/birthday-user",
					data: {
						keyword: this.keyword,
						user_id: this.user_id,
						search_date: this.search_date,
						sign_time: this.sign_time,
						month: this.month,
						day: this.day,
						year: this.year
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list;
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
