<template>
  <div style="text-align: start;">
    <el-dialog title="选择商品" :visible.sync="dialogVisible" width="50%" :before-close="handleClose">
      <div>
        <el-row>
          <el-col :span="12">
            <div class="flex-row">
              <el-input v-model="keyword" placeholder="输入商品名称进行搜索"></el-input>
              <el-button @click="getList" type="primary" style="margin: 0 10px;">搜索</el-button>
            </div>
          </el-col>
        </el-row>
        <el-scrollbar style="height: 450px;">
          <el-table :data="list" style="width: 100%;" v-loading='is_loading'>
            <el-table-column label="商品">
              <template slot-scope="scope">
                <div class="flex-row">
                  <image-view :src="scope.row.cover_pic"></image-view>
                  <div style="padding:0 10px;">
                    {{ scope.row.name }}
                  </div>
                </div>
              </template>
            </el-table-column>
            <el-table-column prop="price" label="价格" width="200">
            </el-table-column>
            <el-table-column prop="stock" label="库存" width="200">
            </el-table-column>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-button size="mini" v-if="scope.row.selected == 1" type="primary"
                  @click="selectRow(scope.row, scope.$index)">
                  已选
                </el-button>
                <el-button size="mini" v-if="scope.row.selected !== 1" @click="selectRow(scope.row, scope.$index)"
                  plain>
                  选择
                </el-button>
              </template>
            </el-table-column>
          </el-table>

        </el-scrollbar>
        <pagination :pagination="pagination" @pageChange="getList" v-model="page"></pagination>
      </div>

      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="confirm">确 定</el-button>
      </span>
    </el-dialog>
    <div style="line-height: normal;" :style="'display:' + (display ? display : 'inline-block')"
      @click="dialogVisible = !dialogVisible">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'GoodsPicker',
  data() {
    return {
      list: [],
      page: 1,
      is_loading: false,
      pagination: null,
      selected_rows: [],
      dialogVisible: false,
      display: '',
      keyword: ''
    }
  },
  created() {
    this.getList();
  },
  methods: {
    getList() {
      this.is_loading = true;
      this.$request({
        url: '/mall/goods/list',
        data: {
          page: this.page,
          keyword: this.keyword,
          status: 1
        }
      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.list = res.data.list;
          for (let i in this.list) {
            this.list[i].selected = 0;
          }
          this.pagination = res.data.pagination
        }
      })
    },
    handleClose() {
      this.dialogVisible = false;
      for (let i in this.list) {
        this.list[i].selected = 0;
      }
      this.selected_rows = [];
    },
    selectRow(row, index) {
      if (row.selected != 1) {
        row.selected = 1;
        this.$set(this.list, index, row)
      } else {
        row.selected = 0;
        this.$set(this.list, index, row)
      }
      this.$forceUpdate();
    },
    confirm() {
      for (let i in this.list) {
        if (this.list[i].selected == 1) {
          this.selected_rows.push(this.list[i])
          this.list[i].selected = 0;
        }
      }
      if (this.selected_rows.length > 0) {
        this.$emit('selected', this.selected_rows)
      } else {
        this.$emit('selected', [])
      }
      this.handleClose();
    }
  }
}
</script>

<style>
</style>
