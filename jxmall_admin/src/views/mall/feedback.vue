<template>
<div class="app-container">
  <el-card class="box-card">
  	<div slot="header" class="clearfix">
  		<span>意见反馈</span>
  	</div>
  	<div>
  		<el-table :data="list" style="width: 100%" v-loading="is_loading">
  			<el-table-column prop="id" label="ID" width="180">
  			</el-table-column>
  			<el-table-column label="反馈用户">
  				<template slot-scope="scope">
  					<div class="flex-y-center">
  						<div>
  							<el-avatar :src="scope.row.avatar_url"></el-avatar>
  						</div>
  						<div>{{scope.row.nickname}}</div>
  					</div>
  				</template>
  			</el-table-column>
  			<el-table-column prop="content" label="反馈内容">
  			</el-table-column>
  			<el-table-column prop="created_time" label="反馈时间">
  			</el-table-column>
  		</el-table>
  		<pagination :pagination="pagination" v-model="page" @pageChange="getList"> </pagination>
  	</div>
  </el-card>


</div>
</template>
<script>
	export default {
    name:'mall-feedback',
		data() {
			return {
				show: false,
				is_loading: false,
				page: 1,
				pagination: null,
				list: [],
			}
		},
		created() {
			this.getList();
		},
		methods: {
			getList() {
				this.is_loading = true;
				this.$request({
					url: "/mall/mall/feedback",
					data: {
						page: this.page
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list;
						this.pagination = res.data.pagination;
					}
				})
			},

		}
	}
</script>

<style>
</style>
