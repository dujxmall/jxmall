<template>
  <div class="app-container">
    <el-card class="box-card" v-loading="is_loading">
      <div slot="header" class="clearfix">
        <span>页面列表</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="addPage">新建页面</el-button>
      </div>

      <el-table :data="list" border style="width: 100%">
        <el-table-column prop="id" label="ID">
        </el-table-column>
        <el-table-column prop="name" label="名称">
        </el-table-column>
        <el-table-column label="页面链接">
          <template slot-scope="scope">
            /pages/diy/diy?id={{scope.row.id}}
          </template>
        </el-table-column>
        <el-table-column label="页面链接">
          <template slot-scope="scope">
            <span v-if="scope.row.is_home==1">
              <el-button type="text">主页</el-button>
            </span>
            <span v-if="scope.row.is_home==0">
              <el-button type="text">其他页面</el-button>
            </span>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button type="text" @click="setHome(scope.row)" v-if="scope.row.is_home==0">设为主页</el-button>
            <el-button type="text" @click="editPage(scope.row)">编辑</el-button>
            <el-button type="text" @click="deletePage(scope.row)">删除</el-button>
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
    name: 'mall-diy',
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
          url: '/mall/mall/diy',
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
      editPage(row) {
        this.$router.push({
          path: '/mall/diy-edit',
          query: {
            id: row.id
          }
        })
      },
      addPage() {
        this.$router.push({
          path: '/mall/diy-edit',
        })
      },
      deletePage(row) {
        this.$confirm('确认删除页面：' + row.name + '？', '提示', {
          type: 'warning'
        }).then(() => {
          this.is_loading = true;
          this.$request({
            url: "/mall/mall/diy-delete",
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
      setHome(row) {

        this.$confirm('确定将页面：' + row.name + '设为主页？', '提示', {
          type: 'warning'
        }).then(() => {
          this.is_loading = true;
          this.$request({
            url: "/mall/mall/diy-home",
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


      }


    }

  }
</script>

<style>
</style>
