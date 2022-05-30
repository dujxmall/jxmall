<template>
	<div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>分销商</span>

        <el-button style="float: right; padding: 3px 0" type="text" @click="addShare">添加分销商</el-button>
      </div>
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
        <el-table-column prop="level" label="分销商等级">
        </el-table-column>
        <el-table-column prop="created_at" label="成为分销商时间">
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

            <div>累计佣金：<span style="color: #FF9952;">{{scope.row.total_price}}</span></div>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button type="text" @click="info(scope.row.user_id)">详情</el-button>
            <el-button type="text" @click="shareOrder(scope.row.user_id)">分销订单</el-button>
          </template>
        </el-table-column>
      </el-table>
      <pagination :pagination="pagination" @pageChange="getList" v-model="page"></pagination>

      <user-picker :show="showUserPicker" @selected="selectUser" @close="closeDialog"></user-picker>
    </el-card>

  </div>


</template>

<script>
	import UserPicker from "@/components/mall/UserPicker.vue"
	export default {
    name: 'plugins-share-list',
		components: {
			UserPicker
		},
		data() {
			return {
				showUserPicker: false,
				pagination: null,
				is_loading: false,
				page: 1,
				list: [],
				dialogVisible: false,
				select_user_id: 0,
			};
		},
		created() {
			this.getList();
		},
		methods: {

			closeDialog(e) {
				this.showUserPicker = false;
			},
			confirmSelect() {
				if (this.select_user_id == 0) {
					this.$message.warning('请选择用户');
					return;
				}
				this.$request({
					url: '/plugin/share/mall/share/add',
					data: {
						user_id: this.select_user_id,
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg)
						this.getList();
					}
				})
			},
			selectUser(e) {

				this.showUserPicker = false;
				if (e.user_id) {
					this.select_user_id = e.user_id;

					this.confirmSelect();
				}


			},

			addShare(e) {

				this.showUserPicker = true
			},
			info(id) {
				this.$router.push({
					path: "/plugins/share/info",
					query: {
						id: id
					}
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
					url: "/plugin/share/mall/share/list",
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
