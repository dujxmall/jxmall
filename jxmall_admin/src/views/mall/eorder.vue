<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>运费模板</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="editEorder(0)">添加</el-button>
      </div>
      <div>
        <el-table :data="list" style="width: 100%">
          <el-table-column prop="id" label="ID" width="100px">
          </el-table-column>
          <el-table-column prop="name" label="模板名称">
          </el-table-column>
          <el-table-column label="快递公司">
            <template slot-scope="scope">
              <div>
                {{scope.row.express.name}}
              </div>
            </template>
          </el-table-column>

          <el-table-column prop="sender_name" label="寄件人">
          </el-table-column>
          <el-table-column prop="sender_mobile" label="寄件人手机号">
          </el-table-column>
          <el-table-column prop="sender_address" label="寄件人地址">
          </el-table-column>


          <el-table-column prop="tpl_style" label="模板样式">
          </el-table-column>
          <el-table-column label="是否默认">
            <template slot-scope="scope">
              <span v-if="scope.row.is_default==1">默认</span>
              <span v-else>否</span>
            </template>
          </el-table-column>
          <el-table-column label="付款方式">
            <template slot-scope="scope">
              <span v-if="scope.row.pay_type==0">现付</span>
              <span v-if="scope.row.pay_type==1">到付</span>
              <span v-if="scope.row.pay_type==2">月结</span>
            </template>
          </el-table-column>

          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type='text' style="margin-right: 10px;" @click="editEorder(scope.row.id)">编辑
              </el-button>
              <el-tooltip class="btn" effect="dark" content="删除" placement="top">
                <el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info"
                               iconColor="red" title="确定删除？" @onConfirm="deleteEorder(scope.row.id)">
                  <el-button type="text" size="mini" slot="reference">删除</el-button>
                </el-popconfirm>
              </el-tooltip>
            </template>
          </el-table-column>
        </el-table>
        <pagination :pagination="pagination" @pageChange="getList" v-model="page"></pagination>
      </div>
    </el-card>


  </div>
</template>

<script>

  export default {
    name: 'mall-eorder',
    data() {
      return {
        page: 1,
        pagination: null,
        list: []
      }
    },
    created() {
      this.getList()
    },
    methods: {
      getList() {
        this.$request({
          url: '/mall/mall/eorder-list',
          data: {
            page: this.page
          }
        }).then(res => {
          if (res.code == 0) {
            this.list = res.data.list;
            this.pagination = res.data.pagination;
          }

        })
      },

      deleteEorder(id) {
        this.$request({
          url: "/mall/mall/eorder-delete",
          data: {
            id: id
          },
          method: 'post',
        }).then(res => {
          if (res.code == 0) {
            this.$message.success(res.msg)
            this.getList();
          }
        })

      },
      editEorder(id) {
        if (id) {
          this.$router.push({
            path: "/mall/eorder-edit",
            query: {
              id: id
            }
          })
        } else {
          this.$router.push({
            path: "/mall/eorder-edit",
          })
        }
      }
    }
  }
</script>

<style>
</style>
