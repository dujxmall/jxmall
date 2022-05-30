<template>

	<div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>文章列表</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="edit(0)">添加文章</el-button>
      </div>
      <el-table :data="list" border style="width: 100%">
        <el-table-column prop="id" label="ID" width="180">
        </el-table-column>
        <el-table-column prop="title" label="标题" width="180">
        </el-table-column>
        <el-table-column prop="created_by" label="作者" width="180">
        </el-table-column>

        <el-table-column prop="created_at" label="创建时间">
        </el-table-column>

        <el-table-column label="操作">
          <template slot-scope="scope">

            <el-button type="text" @click="edit(scope.row.id)"> 编辑</el-button>
            <el-button type="text" @click="del(scope.row)"> 删除</el-button>
          </template>

        </el-table-column>
      </el-table>
      <pagination :pagination="pagination" v-model="page" @pageChange="getList"></pagination>
    </el-card>
  </div>

</template>


<script>
	export default {
    name: 'plugins-article-index',

    data() {
			return {
				page: 1,
				list: [],
				pagination:null
			}
		},
		created() {
			this.getList();
		},
		methods: {

			del(row) {
				
				this.$confirm("确定删除：" + row.title + "?", '提示').then(res => {
					this.$request({
						url: "/plugin/article/mall/article/delete",
						data: {
							id: row.id,
						},
						method:'post'
					}).then(res => {
						if (res.code == 0) {
						     this.$message.success(res.msg)
						}
					})
				}).catch(res => {



				})
			},
			edit(id) {
				if (!id) {
					this.$router.push({
						path: '/plugins/article/edit'
					})
					return;
				};
				this.$router.push({
					path: '/plugins/article/edit',

					query: {
						id: id
					}
				})
			},
			getList() {
				this.$request({
					url: "/plugin/article/mall/article/list",
					data: {
						page: this.page,
					}
				}).then(res => {
					if (res.code == 0) {
						this.pagination=res.data.pagination;
						this.list = res.data.list;
					}
				})

			}

		}
	}
</script>

<style>
</style>
