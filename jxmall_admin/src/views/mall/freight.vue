<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>运费模板</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="editFreight(0)">新建模板</el-button>
      </div>
      <div>
        <el-table :data="list" style="width: 100%">
          <el-table-column prop="id" label="ID">
          </el-table-column>
          <el-table-column prop="name" label="模板名称">
          </el-table-column>
          <el-table-column label="是否默认">
            <template slot-scope="scope">
              <span v-if="scope.row.is_default==1">默认</span>
              <span v-else>否</span>
            </template>
          </el-table-column>
          <el-table-column label="计费方式">
            <template slot-scope="scope">
              <span v-if="scope.row.price_type==1">按重计费</span>
              <span v-else>按件计费</span>
            </template>
          </el-table-column>
          <el-table-column label="状态">
            <!-- 	<template slot-scope='scope'>
  						<el-tooltip class="btn" effect="dark" content="删除" placement="top" v-if="scope.row.status==1">
  							<el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info" iconColor="red" title="是否停用？"
  							 @onConfirm="enableChange(scope.row.id,0)">
  								<el-button type="primary" size="mini" slot="reference">已启用</el-button>
  							</el-popconfirm>
  						</el-tooltip>
  						<el-tooltip class="btn" effect="dark" content="删除" placement="top" v-if="scope.row.status==0">
  							<el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info" iconColor="red" title="是否启用？"
  							 @onConfirm="enableChange(scope.row.id,1)">
  								<el-button type="info" size="mini" slot="reference">未启用架</el-button>
  							</el-popconfirm>
  						</el-tooltip>
  					</template>
   -->
            <template slot-scope="scope">
              <span v-if="scope.row.enabled==1">已启用</span>
              <span v-else>未启用</span>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type='text' style="margin-right: 10px;" @click="editFreight(scope.row.id)">编辑</el-button>
              <el-tooltip class="btn" effect="dark" content="删除" placement="top">
                <el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info" iconColor="red"
                  title="确定删除？" @onConfirm="deleteFreight(scope.row.id)">
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
    name:'mall-freight',
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
          url: '/mall/mall/freight-list',
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
      enableChange(id, status) {


      },
      deleteFreight(id) {
        this.$request({
          url: "/mall/mall/freight-delete",
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
      editFreight(id) {
        if (id) {
          this.$router.push({
            path: "/mall/freight-edit",
            query: {
              id: id
            }
          })
        } else {
          this.$router.push({
            path: "/mall/freight-edit",
          })
        }
      }
    }
  }
</script>

<style>
</style>
