<template>
	<div class="app-container"><el-card class="box-card">
    <div slot="header" class="clearfix">
      <span>分销订单</span>
    </div>
    <div v-loading="is_loading">
      <div style="margin-bottom: 20px;">
        <el-row>
          <el-col :span="6">
            <div class="flex-row">
              <el-input v-model='keyword' placeholder="请输入买家昵称进行搜索"></el-input>
              <el-button type='primary' size='mini' style="margin-left: 10px;" icon='el-icon-search' @click='search'>搜索</el-button>
            </div>
          </el-col>
        </el-row>
      </div>
      <el-table :data="list" style="width: 100%">
        <el-table-column type="expand">
          <template slot-scope="scope">
            <div style="height: 40px;background-color: #F3F3F3;padding: 0 10px;" class="flex-y-center">订单号：{{scope.row.order_no}}</div>
            <el-table :data="scope.row.list" style="width: 100%">
              <el-table-column label="商品信息">
                <template slot-scope="log">
                  <div class="flex-y-center">
                    <el-image :src="log.row.goods.cover_pic" style="width: 60px;height: 60px;"></el-image>
                    <div style="width: 0 10px;">
                      <div>{{log.row.goods.name}}</div>
                      <div class="info-color" style="font-size: 10px;">
                        <div>数量：{{log.row.num}}</div>
                        <div>总价：{{log.row.goods_price}}</div>
                      </div>
                    </div>
                  </div>
                </template>
              </el-table-column>
              <el-table-column label="用户信息">
                <template slot-scope="log">
                  <div class="flex-y-center">
                    <el-avatar :src="log.row.avatar_url"></el-avatar>
                    <div style="padding: 0 10px;">{{log.row.nickname}}</div>
                  </div>
                </template>
              </el-table-column>
              <el-table-column label="佣金" prop="price">

              </el-table-column>
              <el-table-column label="佣金类型" prop="price">
                <template slot-scope="log">
                  <div v-if="log.row.level==1">
                    一级佣金
                  </div>
                  <div v-if="log.row.level==2">
                    二级佣金
                  </div>
                  <div v-if="log.row.level==3">
                    三级佣金
                  </div>
                </template>
              </el-table-column>
              <el-table-column label="佣金状态">
                <template slot-scope="log">
									<span v-if="log.row.status==1">
										<el-button type="primary" size="mini">已发放</el-button>
									</span>
                  <span v-if="log.row.status==0">
										<el-button size="mini">待发放</el-button>
									</span>
                  <span v-if="log.row.status==2">
										<el-button size="mini" type="danger">无效</el-button>
									</span>
                </template>
              </el-table-column>

            </el-table>
          </template>
        </el-table-column>
        <el-table-column label="买家">
          <template slot-scope="scope">
            <div class="flex-y-center">
              <el-avatar :src="scope.row.avatar_url"></el-avatar>
              <div style="padding: 0 10px;">{{scope.row.nickname}}</div>
            </div>
          </template>
        </el-table-column>

        <el-table-column prop="share_price" label="累计佣金">
        </el-table-column>
        <el-table-column label="一级用户">
          <template slot-scope="scope">
            <div class="flex-y-center" v-if="scope.row.parent_1">
              <el-avatar :src="scope.row.parent_1.avatar_url"></el-avatar>
              <div style="padding: 0 10px;">{{scope.row.parent_1.nickname}}</div>
            </div>
            <div class="flex-y-center flex-x-center" v-if="!scope.row.parent_1">
              -
            </div>
          </template>

        </el-table-column>
        <el-table-column label="二级用户">
          <template slot-scope="scope">
            <div class="flex-y-center" v-if="scope.row.parent_2">
              <el-avatar :src="scope.row.parent_2.avatar_url"></el-avatar>
              <div style="padding: 0 10px;">{{scope.row.parent_2.nickname}}</div>
            </div>
            <div class="flex-y-center" v-if="!scope.row.parent_2">
              -
            </div>
          </template>
        </el-table-column>
        <el-table-column label="三级用户">
          <template slot-scope="scope">
            <div class="flex-y-center" v-if="scope.row.parent_3">
              <el-avatar :src="scope.row.parent_3.avatar_url"></el-avatar>
              <div style="padding: 0 10px;">{{scope.row.parent_3.nickname}}</div>
            </div>
            <div class="flex-y-center" v-if="!scope.row.parent_3">
              -
            </div>
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="创建时间">
        </el-table-column>
      </el-table>

      <pagination v-if="pagination" @pageChange="getList" v-model="page" :pagination="pagination"></pagination>
    </div>
  </el-card></div>

</template>

<script>
	export default {
    name: 'plugins-share-order',
		data() {
			return {
				list: [],
				page: 1,
				user_id: '',
				keyword:'',
				is_loading: false,
				pagination: null,
			}
		},
		created() {
			if (this.$router.query && this.$router.query.user_id) {
				this.user_id = this.$router.query.user_id
			}
			this.getList();
		},
		methods: {
			search(){
				  this.page=1;
				  this.pagination=null;
				  this.list=[];
				  this.getList();

			},
			getList() {
				this.is_loading = true;
				this.$request({
					url: "/plugin/share/mall/order/list",
					data: {
						user_id: this.user_id,
						keyword: this.keyword,
						page: this.page
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list
						this.pagination = res.data.pagination
					}
				})

			}
		}

	}
</script>

<style>
</style>
