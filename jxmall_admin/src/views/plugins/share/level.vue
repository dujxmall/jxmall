<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>分销等级</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="editLevel(0)">新增等级</el-button>
      </div>

      <el-table :data="list" style="width: 100%" v-loading="is_loading">
        <el-table-column prop="id" label="ID">
        </el-table-column>
        <el-table-column prop="name" label="等级名称">
        </el-table-column>
        <el-table-column label="等级权重">
          <template slot-scope="scope">
            <span> {{scope.row.level==0?'默认等级':scope.row.level+'级'}}</span>
          </template>
        </el-table-column>
        <el-table-column prop="first_price" label="一级佣金">
        </el-table-column>
        <el-table-column prop="second_price" label="二级佣金">
        </el-table-column>
        <el-table-column prop="third_price" label="三级佣金">
        </el-table-column>
        <el-table-column label="佣金类型">
          <template slot-scope="scope">
					<span v-if="scope.row.price_type==0">
						固定金额
					</span>
            <span v-if="scope.row.price_type==1">
						百分比
					</span>
          </template>

        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button type="text" @click="editLevel(scope.row.id)">编辑</el-button>

            <el-tooltip class="btn" effect="dark" content="删除" placement="top" v-if="scope.row.level!=0">
              <el-popconfirm confirmButtonText='确定' cancelButtonText='取消' icon="el-icon-info" iconColor="red"
                             :title="'确定删除'+scope.row.name+'?'"
                             @onConfirm="deleteLevel(scope.row.id)">
                <el-button style="margin-left: 10px;" slot="reference" type="text">删除</el-button>
              </el-popconfirm>
            </el-tooltip>
          </template>
        </el-table-column>
      </el-table>

    </el-card>

  </div>

</template>

<script>
  export default {
    name: 'plugins-share-level',
    data() {

      return {
        is_loading: false,
        page: 1,


        list: [],


      }
    },
    created() {
      this.getList()
    },
    methods: {
      deleteLevel(id) {

        this.$request({
          url: "/plugin/share/mall/share/level-delete",
          data: {
            id: id
          },
          method: 'post'
        }).then(res => {
          this.is_loading = false;
          if (res.code == 0) {
            this.$message.success(res.msg)
            this.getList();
          }
        }).catch(e => {
          this.is_loading = false;
        })
      },
      getList() {
        this.is_loading = true;
        this.$request({
          url: "/plugin/share/mall/share/level-list",
          data: {
            page: this.page
          }
        }).then(res => {
          this.is_loading = false;
          if (res.code == 0) {
            this.list = res.data.list;

          }
        }).catch(e => {
          this.is_loading = false;
        })
      },
      editLevel(e) {

        if (e == 0) {
          this.$router.push({
            path: '/plugins/share/level-edit'
          })
        } else {
          this.$router.push({
            path: '/plugins/share/level-edit',
            query: {
              id: e
            },
          })
        }
      }
    }

  }
</script>

<style>
</style>
