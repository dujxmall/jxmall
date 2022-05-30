<template>
	<div class="app-container">
    <el-card class="box-card" v-loading='is_loading'>
      <div slot="header" class="clearfix">
        <span>会员等级</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="levelEdit(0)">添加</el-button>
      </div>
      <div>
        <el-table :data="list" style="width: 100%">
          <el-table-column label="等级">
            <template slot-scope="scope">
              等级{{parseInt(scope.row.level)+1}}
            </template>
          </el-table-column>
          <el-table-column prop="name" label="名称">
          </el-table-column>
          <el-table-column label="图标">
            <template slot-scope="scope">
              <el-avatar style="background: none;" size="large" :src="scope.row.pic_url"></el-avatar>
            </template>

          </el-table-column>
          <el-table-column prop="count" label="会员数量">
          </el-table-column>

          <el-table-column label="状态">
            <template slot-scope="scope">
              <div v-if="scope.row.enabled==1"><span style="color:#19BE6B;">已启用</span></div>
              <div v-if="scope.row.enabled==0"><span style="color:  #FF9952;">未启用</span></div>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type="text" style="margin-right: 20px;" @click="levelEdit(scope.row.id)">编辑</el-button>
              <el-tooltip effect="dark" content="删除" placement="top">
                <el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info" iconColor="red" title="确认删除？"
                               @onConfirm="levelDelete(scope.row.id)">
                  <el-button type="text" slot="reference">删除</el-button>
                </el-popconfirm>
              </el-tooltip>
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
	  name:'user-level',
		data() {
			return {
				list: [],
				page: 1,
				is_loading: false,
				pagination: null
			}
		},
		created() {
			this.getList();

		},
		methods: {

			levelEdit(id) {
				if (id == 0) {
					this.$router.push({
						path: '/user/level-edit'
					})
				} else {
					this.$router.push({
						path: '/user/level-edit',
						query: {
							id: id
						}
					})
				}
			},
			levelDelete(id) {
				this.$request({
					url: "/mall/user/level-delete",
					data: {
						id: id
					},
					method: 'post',
				}).then(res => {
					if (res.code == 0) {
						this.getList();
					}
				})

			},
			getList() {
				this.is_loading = true;
				this.$request({
					url: '/mall/user/level',
					data: {
						page: this.page
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
