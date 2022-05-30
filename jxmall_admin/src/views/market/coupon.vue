<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>优惠券</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="couponEdit(0)">添加</el-button>
      </div>
      <div>
        <el-table :data="list" style="width: 100%" v-loading="is_loading">
          <el-table-column prop="id" label="ID" width="100">
          </el-table-column>
          <el-table-column prop="name" label="优惠券名称">
          </el-table-column>
          <el-table-column label="折扣类型">
            <template slot-scope="scope">
              <span v-if="scope.row.type==0">满减券</span>
              <span v-if="scope.row.type==1">折扣券</span>
            </template>
          </el-table-column>
          <el-table-column label="折扣金额">
            <template slot-scope="scope">
              <div v-if="scope.row.type==0">
                <div><span>消费满</span> <span>{{scope.row.price}} 元</span> <span>减</span>
                  <span>{{scope.row.discount}} 元</span>
                </div>
              </div>
              <div v-if="scope.row.type==1">
                <div><span>消费满</span> <span>{{scope.row.price}} 元</span> <span>享受</span>
                  <span>{{scope.row.discount}} 折</span>
                </div>
              </div>
            </template>
          </el-table-column>

          <el-table-column prop="date_range" label="使用日期范围" width="180">
          </el-table-column>
          <el-table-column label="发放总数">
            <template slot-scope="scope">
              <span v-if="scope.row.is_limit_total==0">不限制</span>
              <span v-if="scope.row.is_limit_total==1">{{scope.row.total}}</span>
            </template>
          </el-table-column>
          <el-table-column label="用户最多领取">
            <template slot-scope="scope">
              <span v-if="scope.row.user_total_limit==0">不限制</span>
              <span v-if="scope.row.user_total_limit==1">{{scope.row.user_total}}</span>
            </template>
          </el-table-column>
          <!-- 	<el-table-column label="是否限制商品">
                    <template slot-scope="scope">
                        <span v-if="scope.row.is_goods_limit==0">不限制</span>
                        <span v-if="scope.row.type==1">是</span>
                    </template>
                </el-table-column> -->

          <el-table-column label="用户已领" prop="user_count">

          </el-table-column>
          <el-table-column label="用户失效" prop="user_expire_count">

          </el-table-column>
          <el-table-column label="用户使用" prop="user_use_count">
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type="text" style="margin-right: 10px;" @click="$go.push('/market/coupon-send?id='+scope.row.id)">手动发券
              </el-button>
              <el-button type="text" style="margin-right: 10px;" @click="couponEdit(scope.row.id)">编辑
              </el-button>
              <el-tooltip class="btn" effect="dark" content="删除" placement="top">
                <el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info" iconColor="red"
                  :title="'确定删除'+scope.row.name+'?'" @onConfirm="couponDelete(scope.row.id,scope.$index)">
                  <el-button type='text' size='mini' slot="reference">删除</el-button>
                </el-popconfirm>
              </el-tooltip>
            </template>
          </el-table-column>
        </el-table>
        <pagination :pagination="pagination" v-model="page" @pageChange="getList"> </pagination>
      </div>
    </el-card>

  </div>
</template>
<script>
  export default {
    name:'market-coupon',
    data() {
      return {
        is_loading: false,
        page: 1,
        pagination: null,
        list: []
      }
    },
    created() {

      this.getList();
    },
    methods: {
      couponEdit(id) {
        if (!id) {
          this.$router.push({
            path: "/market/coupon-edit"
          })
        } else {
          this.$router.push({
            path: "/market/coupon-edit",
            query: {
              id: id
            }
          })

        }

      },
      send(id) {
        this.$router.push({
          path: "/market/coupon/send",
          query: {
            id: id
          }
        })
      },
      getList() {
        this.is_loading = true;
        this.$request({
          url: '/mall/market/coupon-list',
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
      couponDelete(id, index) {
        this.is_loading = true;
        this.$request({
          url: '/mall/market/coupon-delete',
          data: {
            id: id
          },
          method: 'post'
        }).then(res => {
          this.is_loading = false;
          if (res.code == 0) {
            this.$message.success(res.msg);
            this.list.splice(index, 1);
          }
        })
      }
    }
  }
</script>

<style>
</style>
