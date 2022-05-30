<template>
<div class="app-container">
  <el-card class="box-card" v-loading="is_loading">
  		<div slot="header" class="clearfix">
  			<span>热图列表</span>
  			<el-button style="float: right; padding: 3px 0" type="text" @click="addImage">新建模板</el-button>
  		</div>
  		<el-table :data="list" border style="width: 100%">
  			<el-table-column prop="id" label="ID">
  			</el-table-column>
  			<el-table-column prop="name" label="名称">
  			</el-table-column>
  			<el-table-column label="操作">
  				<template slot-scope="scope">
  					<el-button type="text" @click="editImage(scope.row)">编辑</el-button>
  					<el-button type="text" @click="deleteImage(scope.row)">删除</el-button>
  				</template>
  			</el-table-column>
  		</el-table>
  		<div style="text-align: right;margin: 20px 0;">
  			<pagination :pagination="pagination" @pageChage="getList">
  			</pagination>
  		</div>
  	</el-card>

</div>
</template>

<script>
	export default {
		name: 'hot-image-list',
		data() {
			return {
				page: 1,
				list: [],
				is_loading: false,
				pagination: null
			}
		},
		created() {
			this.getList();
		},
		methods: {
			getList() {
				this.is_loading = true;
				this.$request({
					url: '/mall/mall/hot-image-list',
					data: {
						page: this.page,
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list
						this.pagination = res.data.pagination
					}
				})
			},
			editImage(row) {
				this.$router.push({
					path: '/mall/hot-image',
					query: {
						id: row.id
					}
				})
			},
			addImage() {
				this.$router.push({
					path: '/mall/hot-image',
				})
			},
			deleteImage(row) {
				this.$confirm('确认删除热图：' + row.name + '？', '提示', {
					type: 'warning'
				}).then(() => {
					this.is_loading = true;
					this.$request({
						url: "/mall/mall/hot-image-del",
						data: {
							id: row.id
						},
						method: 'POST'
					}).then(res => {
						this.is_loading = false;
						if (res.code == 0) {
							this.$message.success(res.msg)
							this.getList();
						}
					})
				}).catch(() => {


				})


			},

		}
	}
</script>
<style>
</style>
