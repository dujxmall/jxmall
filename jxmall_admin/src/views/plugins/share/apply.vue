<template>
<div class="app-container">
  <el-card class="box-card">
    <div slot="header" class="clearfix">
      <span>申请列表</span>

    </div>

    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane label="待审核" name="0"></el-tab-pane>
      <el-tab-pane label="已通过" name="1"></el-tab-pane>
      <el-tab-pane label="已拒绝" name="2"></el-tab-pane>

    </el-tabs>


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

      <el-table-column prop="created_at" label="申请时间">
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
          <div>金额：<span style="color: #FF9952;">{{scope.row.total_order_price}}</span></div>
        </template>
      </el-table-column>
      <el-table-column label="佣金">
        <template slot-scope="scope">
          <div>累计佣金：<span style="color: #FF9952;">{{scope.row.total_price?scope.row.total_price:0}}</span></div>
        </template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <span v-if="scope.row.status==0">待审核</span>
          <span v-if="scope.row.status==1">已通过</span>
          <span v-if="scope.row.status==2">已拒绝</span>

        </template>
      </el-table-column>

      <el-table-column label="操作">
        <template slot-scope="scope">
          <div v-if="scope.row.status==0">
            <el-button type="text" @click="applyHandle(scope.row.id,1)">通过</el-button>
            <el-button type="text" @click="applyHandle(scope.row.id,2)">拒绝</el-button>
          </div>
          <div v-if="scope.row.status!=0">
            <el-button type="text">已处理</el-button>

          </div>
        </template>
      </el-table-column>
    </el-table>
    <pagination :pagination="pagination" @pageChange="getList" v-model="page"></pagination>
  </el-card>

</div>


</template>

<script>
	export default {
    name: 'plugins-share-apply',
		data() {
			return {
				activeName: '0',
				pagination: null,
				is_loading: false,
				page: 1,
				status: 0,
				list: [],

			};
		},
		created() {
			this.getList();
		},
		methods: {
			handleClick(e) {
				console.log(e.name);
				this.status = e.name;
				this.page = 1;
				this.list = [];
				this.getList();
			},
			applyHandle(id, status) {
				let msg = "确认审核通过";
				if (status == 2) {
					msg = "确认拒绝";
				}
				this.$confirm(msg, '提示').then(() => {
					this.$request({
						url: "/plugin/share/mall/share/apply-status",
						data: {
							id: id,
							status: status
						},
						method: 'post'
					}).then(res => {
						if (res.code == 0) {
							this.$message.success(res.msg)
							this.getList();
						}
					})

				}).catch(res => {
					return;
				})


			},
			shareOrder(e) {
				this.$router.push({
					path: "/plugins/share/order",
					query: {
						user_id: e
					}
				})

			},
			getList() {
				this.is_loading = true;
				this.$request({
					url: "/plugin/share/mall/share/apply",
					data: {
						page: this.page,
						status: this.status
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
